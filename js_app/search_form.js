var countriesCombo = Ext.create('Ext.form.ComboBox',{
			fieldLabel : 'Country',
			name : 'country',
			xtype:'combobox',
			store: Ext.create('Ext.data.Store', {
				fields: ['id', 'name'],
				data : countries
			}),
			forceSelection:true,
			allowBlank:false,
			editable:false,
			queryMode: 'local',
			displayField: 'name',
			valueField: 'id'
		});

countriesCombo.setValue("com");

var categoriesCombo = Ext.create('Ext.form.ComboBox',{
			fieldLabel : 'Category',
			name : 'category',
			xtype:'combobox',
			store: Ext.create('Ext.data.Store', {
				fields: ['name'],
				data : categories
			}),
			forceSelection:true,
			allowBlank:false,
			editable:false,
			queryMode: 'local',
			displayField: 'name',
			valueField: 'name'
		});

categoriesCombo.setValue("All");

var formPanel = Ext.create('Ext.form.Panel', {
	frame : true,
	id:'search-form',
	method:"POST",
	width : 200,
	bodyPadding : 1,
	waitMsgTarget : true,
	fieldDefaults : {
		labelAlign : 'top',
		labelWidth : 85,
		msgTarget : 'side'
	},
	items : [ {
		xtype : 'fieldset',
		title : 'Main search',
		defaultType : 'textfield',
		defaults : {
			width : 150
		},
		items : [ countriesCombo,categoriesCombo,{
			fieldLabel : 'Keywords',
			name : 'keywords',
			allowBlank : false
		}]
	}/* ,{
		xtype : 'fieldset',
		title : 'Parameters',
		defaultType : 'textfield',
		collapsible : true,
		collapsed : true,
		defaults : {
			width : 130
		},
		items : [ {
			fieldLabel : 'Other',
			name : 'other',
			allowBlank : true
		}]
	} */],

	buttons : [ {
		text : 'Search',
		handler : function() {
			loadResultGrid(this.up('form').getForm());
			//loadResultGrid();
		}
	} ]
});