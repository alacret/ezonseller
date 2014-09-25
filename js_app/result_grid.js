Ext.define('Result', {
	extend : 'Ext.data.Model',
	fields : [{
		name : 'product_name',
		type : 'string'
	}, {
		name : '_category',
		type : 'string'
	}, {
		name : 'category',
		type : 'string'
	}, {
		name : 'country',
		type : 'string'
	}, {
		name : 'ASIN',
		type : 'string'
	}, {
		name : 'keywords',
		type : 'string'
	}, {
		name : 'rank',
		type : 'int'
	}, {
		name : 'image_url',
		type : 'string'
	}, {
		name : 'image_width',
		type : 'int'
	}, {
		name : 'image_height',
		type : 'int'
	}, {
		name : 'amazon_link',
		type : 'string'
	}, {
		name : 'ccc_link',
		type : 'string'
	}, {
		name : 'ebay_link',
		type : 'string'
		}, {
		name : 'amazon_search_link',
		type : 'string'
		}]
});
function renderGrid(form){

	var store = Ext.create('Ext.data.Store', {
		autoSync: false,
		autoLoad : false,
        remoteSort: true,
		model : 'Result',
		storeId:'result-store',
		//sorters: ['rank'],
		pageSize: 10,
		proxy : {
			type : 'ajax',
			url : "php_app/app.php",
			reader : {
				type : 'json',
				root : 'items',
            	totalProperty: 'results'
			}
		}
	});
	
	store.on('beforeload',function(store,operation,opts){
		if (form.isValid()) {
			var values = form.getValues();	
			for(val in values)
				store.proxy.extraParams[val] = values[val];	
		} else{
			Ext.Msg.alert('Invalid Data','Please correct form errors.');
			return false;
		}
	});
	
	store.on('load',function(store,operation,opts){
		if(operation == null)
			Ext.Msg.alert('Invalid Data','To be available to sort results, you must perform a search where a category is selected. /n Please change the values on the form and clic \'Search\'');
	});
	
	var pagingBar = Ext.create('Ext.toolbar.Paging',{
		    xtype: 'pagingtoolbar',
		    store: store,   // same store GridPanel is using
		    dock: 'bottom',
		    displayInfo: true,
		    id:'paging-bar'
		});
	
	var grid = Ext.create('Ext.grid.Panel', {
		cellType:Ext.create('Ext.selection.RowModel'),
		store : store,
		dockedItems: [pagingBar],
		columns : [{
			dataIndex : 'amazon_link',
			hidden:true
		},{
			dataIndex : 'ccc_link',
			hidden:true
		},{
			dataIndex : 'ebay_link',
			hidden:true
		},{
			dataIndex : 'country',
			hidden:true
		},{
			dataIndex : '_category',
			hidden:true
		},{
			dataIndex : 'keywords',
			hidden:true
		},{
			header : 'Product name',
			dataIndex : 'product_name',
			width:350,
			sortable:false
		},{
			header : 'Category',
			dataIndex : 'category',
			width:250,
			sortable:false
		}, {
			header : 'Asin',
			dataIndex : 'ASIN',
			width:100,
			sortable:false
		}, {
			header : 'Rank',
			dataIndex : 'rank',
			width:100
		}, {
			dataIndex : 'image_url',
			hidden:true
		}, {
			dataIndex : 'image_width',
			hidden:true
		}, {
			dataIndex : 'image_height',
			hidden:true
		}, {
			dataIndex : 'amazon_search_link',
			hidden:true
		}],
		forceFit: true
	});
	
    var resultLinksMarkup = [
        'Product: {product_name}<br/>',
        '<img src="{image_url}" width="{image_width}" height="{image_height}" /> <br/>',
        '<a href="{amazon_link}" target="_blank">Amazon page</a><br/>',
        '<a href="{ccc_link}" target="_blank">Camel product page</a><br/>',
        '<a href="{ebay_link}" target="_blank">Ebay search link</a><br/>'
        ,'<a href="/frames/amazon.php?keywords={keywords}&country={country}&category={_category}" target="left_iframe">Boxed Amazon search link</a><br/>'
        ,'<a href="/frames/ebay.php?keywords={keywords}&country={country}" target="right_iframe">Boxed ebay search link</a><br/>'
    ];
    var resultLinksTemplate = Ext.create('Ext.Template', resultLinksMarkup);	
	
    grid.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
        if (selectedRecord.length) {
            var detailPanel = Ext.getCmp('detail-panel');
            var data = selectedRecord[0].data;
            resultLinksTemplate.overwrite(detailPanel.body, data);
        }
    });
	
	var centerPanel = Ext.getCmp("center-panel-id");
	centerPanel.removeAll(true);
	centerPanel.add(grid);
	
}

function loadResultGrid(form){
	renderGrid(form);
	
	var pagingBar = Ext.getCmp('paging-bar');
	pagingBar.moveFirst();
	/*var store = Ext.getStore("result-store");
	values["start"] = 0;
	values["limit"] = 10;
	values["page"] = 1;
	
	store.load({
	    params: values
	});	*/
}