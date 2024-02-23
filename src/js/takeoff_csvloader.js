class TakeoffLoader {
    #form;
    #button;
    #message;
    #path;
    constructor() {
        this.#form = document.getElementById("takeoff_form");
        this.#button = document.getElementById("takeoff_submit");
        this.#message = document.getElementById("takeoff_message");
        this.#path = this.#getClearPath();
        if (!this.#form || !this.#button || !this.#message) {
            console.warn("No load form found or others");
        }

        this.#activateEvents();
    }

    #activateEvents() {
        this.#form.addEventListener("submit", (e) => this.#startLoading(e));
    }

    #getClearPath() {
        let path = window.takeoff_plugin_path;

        return path.replace(/<script[\s\S]*?<\/script>/gm, "");
    }

    async #startLoading(e) {
        e.preventDefault();
        this.#button.setAttribute("disabled", "true");
        this.#message.style.display = "block";

        const result = await this.#sendRequest();

        if (result.result) {
            this.#message.textContent =
                "FILE WAS UPLOADED, check codes in CODES";
        } else if (!result.result && result.result?.status === 401) {
            this.#message.textContent = `TO UPLOAD FILE, YOU MUST BE LOGGED IN and have 
            permission to edit posts in WP`;
        } else {
            this.#message.textContent = `Couldn't upload file. Try one more time`;
        }

        this.#button.removeAttribute("disabled");
    }

    async #sendRequest() {
        return await fetch("/wp-json/takeoff/v1/process_loading", {
            method: "POST",
            body: new FormData(this.#form),
            headers: {
                "Content-Type": "multipart/form-data",
            },
        }).then((result) => {
            if (result.ok) return { result: true };
            if (result.status === 401)
                return { result: false, message: result.statusText };
            return { result: false };
        });
    }
}

$loader = new TakeoffLoader();
