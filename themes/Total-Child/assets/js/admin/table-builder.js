(function ($) {
    var app = {};
    
    app.Tr = Backbone.Model.extend({
        defaults: {
            order: 0,
            bgColor: 'white',
            col1Content: '',
            centerCol1: false,
            col2Content: '',
            centerCol2: false,
            isCaption: false
        },
        toTemplateData: function () {
            return {
                isCaption: this.get('isCaption'),
                cssClasses1: this.get('centerCol1') ? 'easl-text-center' : '',
                cssClasses2: this.get('centerCol2') ? 'easl-text-center' : '',
                col1Content: this.get('col1Content').replace(/\r\n|\r|\n/g, "<br>"),
                col2Content: this.get('col2Content').replace(/\r\n|\r|\n/g, "<br>")
            };
        }
    });
    app.TrList = Backbone.Collection.extend({
        model: app.Tr,
        comparator: 'order',
    });
    app.trs = new app.TrList();
    
    
    app.EditorView = Backbone.View.extend({
        el: $('#easl-tb-editor'),
        events: {
            'click #easl-tbe-is-caption': 'isCaptionClicked',
            'click .easl-tb-remove': 'cancelEditor',
            'click .easl-tb-save': 'save'
        },
        template: _.template($('#easl-tb-editor-template').html()),
        initialize: function () {
            this.tr = null;
            this.newRow = false;
        },
        loadTrModel: function (tr, newRow) {
            this.tr = tr;
            this.newRow = newRow;
            this.render();
            this.$conten2row = this.$('#tbe-row-content2');
            this.$centercol2row = this.$('#tbe-row-centercol2');
            this.show();
        },
        render: function () {
            return this.$el.html(this.template(this.tr.toJSON()));
        },
        isCaptionClicked: function (e) {
            if (e.target.checked) {
                this.$conten2row.addClass('easl-tb-hide');
                this.$centercol2row.addClass('easl-tb-hide');
            } else {
                this.$conten2row.removeClass('easl-tb-hide');
                this.$centercol2row.removeClass('easl-tb-hide');
            }
        },
        cancelEditor: function (e) {
            e.preventDefault();
            this.hide().$el.html('');
            this.newRow && this.tr.destroy();
        },
        save: function (e) {
            e.preventDefault();
            var rowData = {};
            rowData.isCaption = this.$('#easl-tbe-is-caption').prop('checked');
            rowData.bgColor = this.$('#easl-tbe-is-bg-color').val();
            rowData.col1Content = this.$('#easl-tbe-is-col1content').val();
            rowData.centerCol1 = this.$('#easl-tbe-centercol1').prop('checked');
            rowData.col2Content = !rowData.isCaption ? this.$('#easl-tbe-is-col2content').val() : '';
            rowData.centerCol2 = !rowData.isCaption ? this.$('#easl-tbe-centercol2').prop('checked') : false;
            this.tr.set(rowData);
            this.hide().$el.html('');
        },
        show: function () {
            this.$el.addClass('easl-tb-active');
            return this;
        },
        hide: function () {
            this.$el.removeClass('easl-tb-active');
            return this;
        }
    });
    app.editor = new app.EditorView();
    
    app.TrView = Backbone.View.extend({
        tagName: 'tr',
        setClassName: function () {
            this.$el.attr('class', 'agenda-table-row-' + this.model.get('bgColor'));
        },
        template: _.template($('#easl-tb-row-template').html()),
        events: {
            'click .easl-tb-remove': 'removeRowClicked',
            'click .easl-tb-edit': 'editRowClicked'
        },
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
        },
        render: function () {
            this.setClassName();
            this.$el.html(this.template(this.model.toTemplateData()));
            return this;
        },
        removeRowClicked: function (e) {
            e.preventDefault();
            this.removeRow();
        },
        editRowClicked: function (e) {
            e.preventDefault();
            app.editor.loadTrModel(this.model, false);
        },
        removeRow: function () {
            this.model.destroy();
        }
    });
    
    app.TableBuilder = Backbone.View.extend({
        el: $('#easl-table-builder'),
        events: {
            'click .easl-tb-add-row': 'addRowOnCLick',
        },
        initialize: function () {
            this.$table = this.$('#easl-tb-table');
            this.$dataField = this.$('#easl-tb-table-data');
            
            this.listenTo(app.trs, 'add', this.addRow);
            this.listenTo(app.trs, 'all', this.saveRows);
            
            this.$table.sortable({
                handle: '.easl-tb-sort',
                items: '> tr',
                forcePlaceholderSize: true,
                placeholder: 'sortable-placeholder',
                axis: 'y',
                update: function (e, ui) {
                    app.builder.trViewsSorted();
                }
            });
        },
        render: function () {
            return this;
        },
        trViewsSorted: function () {
            this.$('tr').each(function (order) {
                var trModel = $(this).data('trmodel');
                trModel.set('order', order, {silent: true})
            });
            app.trs.sort();
        },
        addRowOnCLick: function (e) {
            e.preventDefault();
            app.trs.length === 0
                ? app.trs.add({
                    order: 0,
                    bgColor: 'blue',
                    col1Content: 'Caption',
                    centerCol1: true,
                    isCaption: true
                })
                : app.trs.add({
                    order: app.trs.length,
                    bgColor: 'lightblue',
                    col1Content: 'Column 1',
                    col2Content: 'Column 2',
                });
            app.editor.loadTrModel(app.trs.last(), true);
        },
        addRow: function (tr) {
            var row = new app.TrView({
                model: tr
            });
            row.$el.data('trmodel', row.model);
            this.$table.append(row.render().el);
        },
        saveRows: function (e) {
            var rowData = JSON.stringify(app.trs.toJSON());
            this.$dataField.val(rowData);
            
        }
    });
    
    app.builder = new app.TableBuilder();
    
    if (EASL_Table_Builder_Data && EASL_Table_Builder_Data.rows) {
        app.trs.add(EASL_Table_Builder_Data.rows);
    }
})(jQuery);