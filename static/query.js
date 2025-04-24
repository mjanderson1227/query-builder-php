const queryState = {
    pageNumber: 1,
    filterBy: "Equipment Type",
    search: ""
};

const filterByMap = {
    "Equipment Type": "type",
    Manufacturer: "manufacturer",
    "Serial Number": "serial_number",
};

document.addEventListener('DOMContentLoaded', () => {
    /** @type {HTMLFormElement | null} */
    const form = document.getElementById("search-form");

    /** @type {HTMLButtonElement[] | null} */
    const buttons = document.querySelectorAll("#dropdown-button");

    /** @type {HTMLButtonElement | null} */
    const dropdownTrigger = document.getElementById("dropdown-trigger");

    buttons.forEach((button) =>
        button.addEventListener("click", (event) => {
            /** @type {HTMLButtonElement} */
            const element = event.target;
            queryState.filterBy = element.textContent;
            dropdownTrigger.textContent = appState.filterBy;
        }),
    );

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        const formData = new FormData(form);

        queryState.search = formData.get("value");
        const newPageUrl = `?by=${queryState.filterBy}&value=${queryState.search}&page=${queryState.pageNumber}`;
        window.history.pushState({ page: 1 }, "", newPageUrl);
    });
});
