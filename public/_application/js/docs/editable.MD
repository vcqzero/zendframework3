#bootstrap-editable使用
GitHub(https://www.bootcdn.cn/x-editable/)
doc(http://vitalets.github.io/x-editable/docs.html)

#介绍
可将某一dom元素设置为可编辑
也可设置一组元素

#配置说明
App.editable: function(page, config)
其他的设置通过data属性
https://www.bootcdn.cn/x-editable/

##快速入门
指定以下内容
id  
type : text textarea selec 等

config = {
	table_id: {
		url : '/api/website/edit',
		mode:'inline',
	},
	//more table goes here
	//example
	"websiteTable" : {
		url : '/api/website/edit',
		mode:'inline',
	},
}

#post数据
以ajax post 的形式向服务器发生数据

name : name-value
pk : pk-value	1
value:value-value

#服务器返回JSON
需要服务器返回json{success:true/false, msg: ''}
