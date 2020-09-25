$(document).ready(function(){
    $('.selectVidente').on('change', addOption);
});

function addOption(){
	$('.opcaoSelect').val(this.value);
}
