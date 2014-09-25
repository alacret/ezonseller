window.WSapp = function() {
	var iframesPanel = Ext.create('Ext.panel.Panel', {
		layout: {
		    type: 'hbox',
		    pack: 'start',
		    align: 'stretch'
		},
		items: [
		    {html:'<iframe src="/frames/amazon.php" width="100%" height="100%" frameborder="0" name="left_iframe" id="left_iframe"></iframe>', flex:1},
		    {html:'<iframe src="/frames/ebay.php" width="100%" height="100%" frameborder="0" name="right_iframe" id="right_iframe"></iframe>', flex:1}
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
});
