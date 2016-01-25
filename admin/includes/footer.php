

<div class="emptyspace">

</div>


<!-- script src="http://code.jquery.com/jquery-1.11.3.min.js"></script -->

<script src="assets/js/mostrar_nav.js"></script>
<script type="text/javascript">

			function DropDown(el) {
				this.dd = el;
				this.opts = this.dd.find('ul.dropdown > li');
				this.val = [];
				this.index = [];
				this.initEvents();
			}
			DropDown.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						event.stopPropagation();
					});

					obj.opts.children('label').on('click',function(event){
						var opt = $(this).parent(),
							chbox = opt.children('input'),
							val = chbox.val(),
							idx = opt.index();

						($.inArray(val, obj.val) !== -1) ? obj.val.splice( $.inArray(val, obj.val), 1 ) : obj.val.push( val );
						($.inArray(idx, obj.index) !== -1) ? obj.index.splice( $.inArray(idx, obj.index), 1 ) : obj.index.push( idx );
					});
				},
				getValue : function() {
					return this.val;
				},
				getIndex : function() {
					return this.index;
				}
			}

			$(function() {

				var dd = new DropDown( $('.dd') );

				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown').removeClass('active');
				});

			});

</script>
<script>
$(document).ready(function(){
	$('#hamburger').click(function(){
		$(this).toggleClass('open');
	});
});
</script>
