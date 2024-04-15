document.addEventListener("DOMContentLoaded", (event) => {

    let form = document.getElementById("action-form");
    let nav = document.getElementById("nav");
    let actionButtons = document.querySelectorAll("button.btn-action");

    actionButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            event.preventDefault();
            this.innerText = "wait...";
            this.style.color = 'lightgrey';

            document.getElementsByName('container_id')[0].setAttribute('value', this.dataset.containerId);
            document.getElementsByName('action')[0].setAttribute('value', this.dataset.action);
            form.submit();
        });
    });

    document.getElementById("menu-toggle").addEventListener("click", function(event) {
        event.preventDefault();

        if (nav.className === "nav") {
            nav.className += " nav--open";
        } else {
            nav.className = "nav";
        }

        this.classList.toggle("menu-toggle--open");
    });

    document.getElementById("follow-logs")?.addEventListener("click", function(event) {
        event.preventDefault();
        let container = this.dataset.containerId;

        let watcher = new EventSource(BASE_URL + "/logs/watch/" + container);
        let triggerBtn = this;
        let stopper = document.getElementById("stop-logs");

        stopper.style.display = 'inline-block';
        triggerBtn.style.display = 'none';

        watcher.addEventListener("message", function (evt)  {
            console.log(evt.data);
            // Add message to container
        });

        stopper.addEventListener("click", function(event) {
            watcher.close();
            this.style.display = 'none';
            triggerBtn.style.display = 'inline-block';
        });

        watcher.onerror = (err) => console.error("EventSource failed:", err);
        window.onbeforeunload = (evt) => watcher?.close();
    });

});