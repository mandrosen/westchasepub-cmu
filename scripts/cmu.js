function toggle(id) {
	var ele = document.getElementById(id);
	if (ele) {
		if (ele.style.display == "block") ele.style.display = "none";
		else ele.style.display = "block";
	}
}