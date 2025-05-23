/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 * https://www.jqueryscript.net/other/jQuery-Drag-drop-Sorting-Plugin-For-Bootstrap-html5sortable.html 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function ($) {
  var dragging, placeholders = $();
  $.fn.sortable = function (options) {
    var method = String(options);
    options = $.extend({
      connectWith: false,
      placeholderClass: ''
    }, options);
    return this.each(function () {
      if (/^enable|disable|destroy$/.test(method)) {
        var dragitems = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
        if (method == 'destroy') {
          dragitems.add(this).removeData('connectWith items')
            .off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
        }
        return;
      }
      var isHandle, parent, index, items = $(this).children(options.items);
      var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : /^tbody$/i.test(this.tagName) ? 'tr' : 'div') +
                          ' class="sortable-placeholder ' + options.placeholderClass + '">').html('&nbsp;');
      items.find(options.handle).mousedown(function () {
        isHandle = true;
      }).mouseup(function () {
        isHandle = false;
      });
      $(this).data('items', options.items)
      placeholders = placeholders.add(placeholder);
      if (options.connectWith) {
        $(options.connectWith).add(this).data('connectWith', options.connectWith);
      }
      items.attr('draggable', 'true').on('dragstart.h5s', function (e) {
        if (options.handle && !isHandle) {
          return false;
        }
        isHandle = false;
        var dt = e.originalEvent.dataTransfer;
        dt.effectAllowed = 'move';
        dt.setData('Text', 'dummy');
        index = (dragging = $(this)).addClass('sortable-dragging').index();
        parent = dragging.parent();
      }).on('dragend.h5s', function () {
        if (!dragging) {
          return;
        }
        dragging.removeClass('sortable-dragging').show();
        placeholders.detach();
        if (index != dragging.index()) {
          dragging.parent().trigger('sortupdate', {
            item: dragging
          });
        }
        if (!dragging.parent().is(parent)) {
          dragging.parent().trigger('sortconnect', {
            item: dragging
          });
        }
        dragging = null;
      }).not('a[href], img').on('selectstart.h5s', function () {
        this.dragDrop && this.dragDrop();
        return false;
      }).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function (e) {
        if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
          return true;
        }
        if (e.type == 'drop') {
          e.stopPropagation();
          placeholders.filter(':visible').after(dragging);
          dragging.trigger('dragend.h5s');
          return false;
        }
        e.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'move';
        if (items.is(this)) {
          if (options.forcePlaceholderHeight || options.forcePlaceholderSize) {
            placeholder.height(dragging.outerHeight());
          }
          if (options.forcePlaceholderWidth || options.forcePlaceholderSize) {
            placeholder.width(dragging.outerWidth());
          }
          dragging.hide();
          $(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
          placeholders.not(placeholder).detach();
        } else if (!placeholders.is(this) && !$(this).children(options.items).length) {
          placeholders.detach();
          $(this).append(placeholder);
        }
        return false;
      });
    });
  };
})(jQuery);
