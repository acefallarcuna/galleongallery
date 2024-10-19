class navBar extends HTMLElement {
    connectedCallback() {
    this.innerHTML = `
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">My Navbar</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            </ul>
        </div>
        </nav>
    `;
    }
}

customElements.define('nav-bar', navBar);