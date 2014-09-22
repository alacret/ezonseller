window.WSapp = function() {
	var iframesPanel = Ext.create('Ext.panel.Panel', {
		layout: {
		    type: 'hbox',
		    pack: 'start',
		    align: 'stretch'
		},
		items: [
		    {html:'<iframe src="http://www.amazon.com" width="100%" height="100%" frameborder="0" name="left_iframe"></iframe>', flex:1},
		    {html:'<iframe src="http://www.ebay.com" width="100%" height="100%" frameborder="0" name="right_iframe"></iframe>', flex:1}
		],
		height:250
	});
	
	var gridPanel = Ext.create('Ext.panel.Panel', {
		id:'center-panel-id'
	});	
	
	var centerPanel = Ext.create('Ext.panel.Panel', {
		region : 'center',
		overflow:'scroll',
		items:[gridPanel,iframesPanel]
	});
	
	var optPanel = Ext.create('Ext.panel.Panel', {
		region : 'east',
		collapsible : true,
		id: 'detail-panel',
		//split : true,
		frame:true,
		width : 180,
		 layout: {
		        type: 'vbox',       // Arrange child items vertically
		        align: 'stretch',    // Each takes up full width
		        padding: 5
		    }
	});

	var cabecera = Ext.create('Ext.panel.Panel', {
		html : '<div class="logo"></div>',
		height:105
	});

	Ext.create('Ext.container.Viewport', {
		layout : 'border',
		items : [ {
			region : 'north',			
			items : [ cabecera ]
		}, {
			region : 'west',
			collapsible : true,
			width : 200,
			title : 'Search form',
			split : true,
			frame:false,			
			items : [ formPanel ]
		}, optPanel,centerPanel ]
	});
	
};

Ext.onReady(function() {
	window.WSapp();
	/*
	var bienvenidosPanel = Ext.create('Ext.panel.Panel', {
	    title: 'Bienvenidos',
	    html: '<div class="bienvenidos"><img src="resources/bienvenido.jpg" /></div>'
	});


	var formPanelLogin = Ext.create('Ext.form.Panel', {
		frame : true,
		url : window.ROOT_URL+"/app.php?action=login-user",
		title : 'Members',
		width : 300,
		bodyPadding : 5,
		waitMsgTarget : true,
		fieldDefaults : {
			labelAlign : 'right',
			labelWidth : 85,
			msgTarget : 'side'
		},
		items : [ {
			xtype : 'fieldset',
			title : 'Login information',
			defaultType : 'textfield',
			defaults : {
				width : 280
			},
			items : [ {
				fieldLabel : 'Username',
				name : 'username',
				allowBlank:false,
				value:"admin"
			}, {
				fieldLabel : 'Password',
				name : 'password',
				inputType: 'password',
				allowBlank:false,
				value:"adminadmin"
			} ]
		} ],

		buttons : [ {
			text : 'Login',
			handler : function() {
				var form = this.up('form').getForm(); // get the basic form
				if (form.isValid()) { // make sure the form contains valid
										// data before submitting
					form.submit({
						success : function(form, action) {
							if(action.result.success == "true"){
								window.WSapp(action.result);
								win.close();
							}else
								Ext.Msg.alert('Error', "Invalid username or password");
						},
						failure : function(form, action) {
							Ext.Msg.alert('Failed', action.result.msg);
						}
					});
				} else { // display error alert if the data is invalid
					Ext.Msg
							.alert('Invalid Data',
									'Please correct form errors.')
				}
			}
		} ]
	});

	var tabPanel = Ext.create('Ext.tab.Panel', {
		width : 350,
		height : 300, 
		activeTab: 0
	});

	var win = Ext.create('widget.window', {
		title : 'Welcome',
		closeAction : 'hide',
		width : 365,
		height : 330
	});
	
	tabPanel.add(bienvenidosPanel);
	tabPanel.add(formPanelLogin);
	tabPanel.add(formPanel);
	
	win.add(tabPanel);
	
	win.show();
	//window.WSapp(true);
	*/
	
});
