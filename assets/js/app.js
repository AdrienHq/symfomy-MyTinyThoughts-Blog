import '../css/app.scss';
import {Dropdown} from "bootstrap";

document.addEventListener('DOMContentLoaded', () => {
    enableDropdown();
})
const enableDropdown = () => {
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    dropdownElementList.map(function (dropdownToggleEl) {
        return new Dropdown(dropdownEl)
    });
}