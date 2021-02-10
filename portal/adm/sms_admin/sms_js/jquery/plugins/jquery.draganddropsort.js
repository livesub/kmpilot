(function($)
{
	// This script was written by Steve Fenton
	// http://www.stevefenton.co.uk/Content/Jquery-Drag-And-Drop-Sort/
	// Feel free to use this jQuery Plugin
	// Version: 3.1.0
	
	$.fn.draganddropsort = function (settings) {
	
		var config = {
			classmodifier: "dds",
			appendlastline: true
		};
		
		if (settings) {
			$.extend(config, settings);
		}

		var nextSetIdentifier = 0;
		var currentItem = null;
		var currentTarget = null;
		var itemInTransit = false;
		
		var insertClass;
		var movingClass;
		var itemClass;
		
		insertClass = config.classmodifier + "insert";
		movingClass = config.classmodifier + "moving";
		itemClass = config.classmodifier + "item";
		
		// Captures drops
		$(document).mouseup( function () {
			if (itemInTransit && currentTarget != null) {
				var clone = $(currentItem).clone();
				bindEvents(clone);
				$(currentTarget).before(clone);
				$(currentItem).remove();
			}
			currentItem = null;
			currentTarget = null;
			itemInTransit = false;
			$("." + movingClass).removeClass(movingClass);
		});
	
		// Bind the drag drop events
		function bindEvents(item) {
		
			var $Item = $(item);
		
			$Item.mousedown( function () {
				currentItem = $(this);
				itemInTransit = true;
				$(this).addClass(movingClass);
				return false;
			});
			
			$Item.mouseenter( function () {
				if (itemInTransit) {
					currentTarget = $(this);
					currentTarget.addClass(insertClass);
					return false;
				}
			});
			
			$Item.mouseout( function () {
				currentTarget = null;
				$(this).removeClass(insertClass);
			});
		}
		
		return this.each(function () {
		
			var $This = $(this);
			
			// Append a spare line, which allows items to be dragged past the last item
			if (config.appendlastline) {
				var clone = $This.children().first().clone();
				var children = $(clone).children();
				if (children.length == 0) {
					$(clone).html("&nbsp;21");
				} else {
					$(clone).children().each(function () {
						$(this).html("&nbsp");
					});
				}
				$This.append(clone);
			}
		
			// Bind events for sortable items
			$This.children().each(function () {
				bindEvents(this);
				$(this).addClass(itemClass);
			});
			
			nextSetIdentifier++;
		});
	};
})(jQuery);