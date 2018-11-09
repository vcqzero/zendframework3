define(['jquery'], function() {
	var EVENT_CHECKED_NODE = 'myResourceTree.chechNode'

	var triggerChechEvent = function(container, tree) {
		var checkedResourcesAsJson = getCheckedResourcesJson(tree)
		container.trigger(EVENT_CHECKED_NODE, [checkedResourcesAsJson])
	}

	var getCheckedResourcesJson = function(tree) {
		var nodes = tree.treeview('getEnabled')
		//		var disabledNodes = tree.treeview('getDisabled')
		//		console.log(enabledNodes)
		//		console.log(disabledNodes)
		//		$.each(disabledNodes, function(k, v) {
		//			nodes.push(v)
		//		});

		var checkedNodes = []
		var parents = []
		$.each(nodes, function(k, v) {
			if(v.state.checked) {
				if(v.parentId === undefined) {
					parents.push(v)
				}
				checkedNodes.push(v)
			}
		})
		var checked = {}
		$.each(parents, function(k, v) {
			var controller = v.text
			var controller_id = v.nodeId
			checked[controller] = {}
			$.each(checkedNodes, function(_k, _v) {
				var parentId = _v.parentId
				var action = _v.text
				if(parentId == controller_id) {
					checked[controller][_k] = action
				}
			});
		})

		return JSON.stringify(checked)
	}

	var getTreeList = function(resourcesAsObj, inheritResources, roleResources) {
		var treeList = []
		$.each(resourcesAsObj, function(controller, actions) {
			var inheritActions = inheritResources[controller]
			var checkedActions = roleResources[controller]
			var nodes = getNodes(actions, inheritActions, checkedActions)
			//			console.log(inheritResources[controller])
			var tree = {
				text: controller,
				nodes: nodes,
				state: {
					expanded: false,
				},
				//				tags : ['jklshfklshshkhsh']
			}
			if(inheritActions !== undefined || checkedActions !== undefined) {
				tree.state.checked = true
				//				tree.state.disabled = true
				//				tree.state.expanded = true
			}
			treeList.push(tree)
		});
		return treeList
	}

	var getNodes = function(actions, inheritActions, checkedActions) {
		var nodes = []
		$.each(actions, function(k, action) {
			var _node = {
				text: action,
				state: {}
			}

			$.each(inheritActions, function(k, v) {
				if(action == v) {
					_node.state.checked = true
					_node.state.disabled = true
					return false;
				}
			})

			$.each(checkedActions, function(k2, v2) {
				if(action == v2) {
					_node.state.checked = true
					return false;
				}
			})

			nodes.push(_node)
		});

		return nodes
	}

	var hasCheckedKidNode = function(_node) {
		var children = _node['nodes']
		var hasChecked = false
		if(children === undefined) {
			return false;
		}
		//		console.log(children)
		$.each(children, function(v, kid_node) {
			if(kid_node.state.checked === true) {
				hasChecked = true
				return false;
			}
		})
		return hasChecked
	}

	var isKidNode = function(_node, tree) {
		if(_node['parentId'] !== undefined) {
			return true;
		} else {
			return false;
		}
	}

	var onKidNodeChecked = function(kidNode, tree) {
		var parentId = kidNode.parentId
		tree.treeview('checkNode', [parentId, {
			silent: true
		}])
	}

	var onKidNodeUnchecked = function(kidNode, tree) {
		var parentId = kidNode.parentId
		var parentNode = tree.treeview('getParent', kidNode)
		if(hasCheckedKidNode(parentNode) === false) {
			console.log(parentId)
			tree.treeview('uncheckNode', [parentId, {
				silent: true
			}])
		}
	}

	var onParentNodeUnchecked = function(_node, tree) {
		var children = _node['nodes']
		var nodeId = _node['nodeId']
		$.each(children, function(v, kid_node) {
			var kidNodeId = kid_node['nodeId']
			if(kid_node.state.disabled !== true) {
				tree.treeview('uncheckNode', [kidNodeId, {
					silent: true
				}])
			} else {
				tree.treeview('checkNode', [nodeId, {
					silent: true
				}])
			}
		})
	}

	return {
		init: function(container, resourcesAsJson, inheritResources, roleResources) {
			if(inheritResources === undefined || inheritResources == null) {
				inheritResources = {}
			}

			if(roleResources === undefined || roleResources == null) {
				roleResources = {}
			}

			var resourcesAsObj = JSON.parse(resourcesAsJson)
			var treeList = getTreeList(resourcesAsObj, inheritResources, roleResources)
			container.treeview({
				data: treeList, // data is not optional
				multiSelect: true,
				highlightSelected: false,
				showCheckbox: true,
				showTags: true,

				onNodeSelected: function(event, data) {
					var nodeId = data['nodeId']
					$(this).treeview('toggleNodeExpanded', [nodeId, {
						silent: true
					}]);
				},

				onNodeUnselected: function(event, data) {
					var nodeId = data['nodeId']
					$(this).treeview('toggleNodeExpanded', [nodeId, {
						silent: true
					}]);
				},

				onNodeChecked: function(event, _node) {
					var tree = $(this)
					var children = _node['nodes']
					var parentId = _node.parentId
					if(isKidNode(_node)) {
						onKidNodeChecked(_node, tree)
					} else {
						$.each(children, function(v, kid_node) {
							var nodeId = kid_node['nodeId']
							tree.treeview('checkNode', [nodeId, {
								silent: true
							}])
						})
					}
					triggerChechEvent(container, tree)
				},

				onNodeUnchecked: function(event, _node) {
					var tree = $(this)
					console.log('反选')
					if(isKidNode(_node, tree)) {
						onKidNodeUnchecked(_node, tree)
					} else {
						onParentNodeUnchecked(_node, tree)
					}
					triggerChechEvent(container, tree)
				},
			})
		}
	}
})