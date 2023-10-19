const nav = navigator;
// @ts-ignore
const doc = document;
const body = doc.body;

let checkDone = false;

doc.addEventListener("click", (event) => {
	const target = event.target;
	if (
		target instanceof Element &&
		target.classList.contains("papertiger-fieldnames__button")
	) {
		const text = target.textContent;
		copy(text);
	}
});

function copyNotAllowed() {
	if (checkDone) {
		return;
	}
	checkDone = true;
	body.classList.add("papertiger-clipboard--not-allowed");
}

function copy(text) {
	return new Promise<void>((resolve, reject) => {
		if (
			typeof nav !== "undefined" &&
			typeof nav.clipboard !== "undefined" &&
			typeof nav.permissions !== "undefined"
		) {
			const type = "text/plain";
			const blob = new Blob([text], { type });
			const data = [new ClipboardItem({ [type]: blob })];
			nav.permissions
				.query({ name: "clipboard-write" as PermissionName })
				.then((permission) => {
					if (
						permission.state === "granted" ||
						permission.state === "prompt"
					) {
						nav.clipboard
							.write(data)
							.then(resolve, reject)
							.catch(reject);
					} else {
						copyNotAllowed();
						reject(new Error("Permission not granted!"));
					}
				});
		} else if (
			doc.queryCommandSupported &&
			doc.queryCommandSupported("copy")
		) {
			let textarea = doc.createElement("textarea");
			textarea.textContent = text;
			textarea.style.position = "fixed";
			textarea.style.width = "2em";
			textarea.style.height = "2em";
			textarea.style.padding = "0";
			textarea.style.border = "none";
			textarea.style.outline = "none";
			textarea.style.boxShadow = "none";
			textarea.style.background = "transparent";
			body.appendChild(textarea);
			textarea.focus();
			textarea.select();
			try {
				doc.execCommand("copy");
				body.removeChild(textarea);
				resolve();
			} catch (e) {
				body.removeChild(textarea);
				copyNotAllowed();
				reject(e);
			}
		} else {
			copyNotAllowed();
			reject(
				new Error(
					"None of copying methods are supported by this browser!"
				)
			);
		}
	});
}
