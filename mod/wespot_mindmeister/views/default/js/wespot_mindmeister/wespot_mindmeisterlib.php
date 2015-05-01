
function toggleWespotMindMeisterDetails(id, buttonright, buttondown) {
	var detailsdiv = document.getElementById('detailsdiv'+id);
	var detailsdivbutton = document.getElementById('detailsdivbutton'+id);
	if (detailsdiv && detailsdiv.style.display == 'block') {
		detailsdiv.style.display ='none';
		detailsdivbutton.src = 	buttonright;
	} else if (detailsdiv && detailsdiv.style.display == 'none') {
		detailsdiv.style.display ='block';
		detailsdivbutton.src = buttondown;
	}
}