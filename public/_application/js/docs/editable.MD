#bootstrap-editable使用
GitHub(https://www.bootcdn.cn/x-editable/)
doc(http://vitalets.github.io/x-editable/docs.html)

##快速入门
将以下配置文件传入App.editable: function(page, config)中即可完成
https://www.bootcdn.cn/x-editable/

config = [
{
	target: $('#realname'),//$元素
	option: {
		url: edit_url,
		...可配置其他选项
	},
},
...
]

#post数据
以ajax post 的形式向服务器发生数据

name : value //来自元素的id或data-name
pk : vaule//来自元素中的data-pk或option中的pk
value:vaule//元素值，text select 等元素的内容

#服务器返回JSON
需要服务器返回json{success:true/false, msg: ''}
如果返回false则msg会被显示