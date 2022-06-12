
function pophapus(id) {
	document.getElementById('idpop').value = id;
	var pop1 = document.getElementById('popdel');
	var pop2 = document.getElementById('boxdel');
	pop1.style.visibility = 'visible';
	pop2.style.visibility = 'visible';
	pop2.style.top = '35%';
	pop1.style.transition = '1s';
	pop2.style.transition = '0.5s';
};
		
function pophidden() {
	var pop1 = document.getElementById('popdel');
	var pop2 = document.getElementById('boxdel');
	pop1.style.visibility = 'hidden';
	pop2.style.visibility = 'hidden';
	pop2.style.top = '25%';
	pop1.style.transition = '0s';
	pop2.style.transition = '0s';			
};
