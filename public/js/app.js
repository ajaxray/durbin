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

    // var source = new EventSource("http://localhost:8081/logs");
    // source.onmessage = function(event) {
    //     console.log(event);
    // };
    // source.onerror = (err) => {
    //     console.error("EventSource failed:", err);
    // };

});