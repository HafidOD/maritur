<div class="modal-header">
	@hasSection('header-content')
	    @yield('header-content')
	@else
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3 class='modal-title'> @yield('title') </h3>
	@endif
</div>
<div class="modal-body">
    @yield('body-content')
</div>
@hasSection('footer-content')
<div class="modal-footer">
    @yield('footer-content')
</div>
@endif
