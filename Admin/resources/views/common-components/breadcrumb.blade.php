<div class="page-title-box breadcumb">
	<div class="float-right">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">{{ $item1 }}</li>
			<li class="breadcrumb-item active">{{ $title }}</li>
		</ol>
	</div>
	@if(Request::path() == '/')
		<div class="d-flex align-items-center">
			<h4 class="page-title">{{$title}}</h4>
			<div class="tooltip-hint about-gerencial" data-title="Clique aqui e entenda a tela gerencial">
				<img class="ml-2" src="assets/images/widgets/youtube.png" alt="Entenda a tela gerencial !"
				data-toggle="modal" data-target="#about-gerencial">
			</div>
		</div>
	@else
		<h4 class="page-title">{{ $title }}</h4>
	@endif
</div>
