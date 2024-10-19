class sideNav extends HTMLElement {
    connectedCallback() {
    this.innerHTML = `
        <style>
        /* Basic styles for the side nav */
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa; /* Bootstrap light background */
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidenav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
        }
        .sidenav a:hover {
            background-color: #ddd; /* Hover effect */
        }
        </style>
        <div class="sidenav">
        <h2>SideNav</h2>
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Contact</a>
        </div>
    `;
    }
}
customElements.define('side-nav', sideNav);