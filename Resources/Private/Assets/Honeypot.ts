const events = ["touchstart", "keydown", "mousemove", "touchmove"];
// @ts-ignore
const doc = document;
const dataset: { name?: string; value?: string } =
	doc.currentScript?.dataset || {};

function insertSpamProtectionValue() {
	const field = doc.getElementById(dataset.name || "");
	const value = dataset.value || "";
	if (field && value) {
		field.setAttribute("value", value);
	}
	removeEventListeners();
}


function addEventListeners() {
	events.forEach((event) => {
		doc.addEventListener(event, insertSpamProtectionValue, true);
	});
}

function removeEventListeners() {
	events.forEach((event) => {
		doc.removeEventListener(event, insertSpamProtectionValue, true);
	});
}

addEventListeners();
