	@foreach ($irs as $ir)
  @if ($ir->referenceModel)
  <li>
	 <a href="/hotels/{{$ir->referenceModel->path}}">
	    <div class="top-elements">
	      <img src="{{$ir->referenceModel->getPrimaryImageUrl(280,280)}}">
	      <p class="text-middle"><strong>{{$ir->referenceModel->name}}</strong></p>
	      <span class="box-back"></span>
	      <span class="box-border"></span>
	    </div>                
	  </a>
  </li>
  @endif
@endforeach
