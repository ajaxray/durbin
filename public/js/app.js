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
        console.log("WATCHING: "+ container);

        let watcher = new EventSource(BASE_URL + "/logs/watch/" + container);
        let triggerBtn = this;
        let stopperBtn = document.getElementById("stop-logs");
        let clearBtn = document.getElementById("clear-logs");
        let screen = document.getElementById("screen");

        stopperBtn.style.display = 'inline-block';
        clearBtn.style.display = 'inline-block';
        triggerBtn.style.display = 'none';
        screen.innerHTML = '';

        watcher.addEventListener("message", function (evt)  {
            console.log(evt.data);
            screen.innerHTML += '<div>'+ evt.data +'</div>';
        });

        clearBtn.addEventListener("click", function(event) {
            screen.innerHTML = '';
        });

        stopperBtn.addEventListener("click", function(event) {
            watcher.close();
            this.style.display = 'none';
            clearBtn.style.display = 'none';
            triggerBtn.style.display = 'inline-block';
        });

        watcher.onerror = (err) => console.error("EventSource failed:", err);
        window.onbeforeunload = (evt) => watcher?.close();
    });

});