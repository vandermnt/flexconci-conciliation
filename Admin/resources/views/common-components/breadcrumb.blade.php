

<div class="page-title-box breadcumb">
	@if(Request::path() == '/')
		<div class="float-right">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">{{ $item1 }}</li>
				<!-- <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ $item2 }}</a></li> -->
				<li class="breadcrumb-item active">{{ $title }}</li>
			</ol>
		</div>
		<div class="d-flex align-items-center">
			<h4 class="page-title">{{$title}}</h4>
			<div class="tooltip-hint about-gerencial" data-title="Entenda a tela gerencial !">
				<img class="ml-2" src="assets/images/widgets/youtube.png" alt="Entenda a tela gerencial !">
			</div>
		</div>
	@else
		<div class="float-right">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">{{ $item1 }}</li>
				<!-- <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ $item2 }}</a></li> -->
				<li class="breadcrumb-item active">{{ $title }}</li>
			</ol>
		</div>
		<h4 class="page-title">{{ $title }}</h4>
	@endif
</div><!--end page-title-box-->
