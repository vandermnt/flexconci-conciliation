const aboutGerencialVideo = document.querySelector('#aboutGerencialVideo');

document
	.querySelector('#about-gerencial-modal')
	.addEventListener('click', aboutGerencialModalHandleClose);
function aboutGerencialModalHandleClose() {
	console.log('Close modal');
	// aboutGerencialVideo.pause();
}
