      document.addEventListener("DOMContentLoaded", function () {


            const input = document.getElementById("coverInput");
            const preview = document.getElementById("coverPreview");

            if (!preview) return;
            if (!input) return;

            input.addEventListener("change", function () {

                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.style.backgroundImage = `url('${e.target.result}')`;
                    preview.style.backgroundSize = "cover";
                    preview.style.backgroundPosition = "center";
                };

                reader.readAsDataURL(file);
            });

        });
        document.addEventListener("DOMContentLoaded", function () {
            const popup = document.getElementById("imgPopup");
            const popupImg = document.getElementById("popupImg");
            const closePopup = document.getElementById("closePopup");

            const preview = document.getElementById("coverPreview");
            if (preview) {
                preview.addEventListener("click", function () {
                    const bg = preview.style.backgroundImage;
                    if (bg && bg !== "none") {
                        const url = bg.slice(5, -2);
                        popupImg.src = url;
                        popup.style.display = "flex";
                    }
                });
            }
            closePopup.addEventListener("click", function () {
                popup.style.display = "none";
            });

            popup.addEventListener("click", function (e) {
                if (e.target === popup) popup.style.display = "none";
            });
        });