blockSetup = (current_block) => {
	let parent_block = current_block.closest(".wp-block");
	parent_block.style = current_block.getAttribute("style");

	if (parent_block !== null) {
		parent_block.addEventListener("click", window.handleImageClicks);
	}
};

window.handleImageClicks = (event) => {
	const parent = event.currentTarget;
	const target = event.target;
	const button_group = document.querySelector(".acf-block-panel .acf-fields .acf-image-uploader");
	/* Add Image, Edit, and Remove Buttons */
	const isAddImage = target.classList.contains("acf-button");
	const isEdit = target.classList.contains("-pencil");
	const isRemove = target.classList.contains("-cancel");

	/* Add Image Button */
	if (isAddImage || isEdit || isRemove) {
		const toolbar = document.querySelector(".edit-post-visual-editor .components-popover");
		if (toolbar !== null) {
			const edit_button = toolbar.querySelector(".block-editor-block-toolbar__slot button > span.dashicons").parentElement;
			if (edit_button !== null) {
				edit_button.click();
				setTimeout(() => {
					if (isEdit || isRemove) {
						const target_class = isEdit ? ".-pencil" : ".-cancel";
						const target_button = parent.querySelector(".show-if-value a" + target_class);
						if (target_button !== null) {
							target_button.style.display = "block";
							target_button.click();
							target_button.style.display = "";
						}
					} else {
						const add_image_button = parent.querySelector(".acf-field-image .acf-image-uploader .acf-button");
						if (add_image_button !== null) {
							add_image_button.click();
						}
					}
					if (isAddImage || isEdit) {
						setTimeout(() => {
							edit_button.click();
						}, 20);
					}
				}, 20);
				return;
			}
		}
		/* If popup toolbar does not exist */
		if (isEdit || isRemove) {
			const target_class = isEdit ? ".-pencil" : ".-cancel";
			const target_button = button_group.querySelector(".show-if-value a" + target_class);
			if (target_button !== null) {
				target_button.style.display = "block";
				target_button.click();
				target_button.style.display = "";
			}
			return;
		}
		if (isAddImage) {
			const add_image_button = button_group.querySelector(".hide-if-value a");
			if (add_image_button !== null) {
				add_image_button.click();
			}
			return;
		}
		console.warn("toolbar must be open while selecting images");
	}
};
