export default class extends window.Controller {

    /**
     * Sets default hidden columns
     */
    hiddenAllColumns() {
        this.element.querySelectorAll('input[data-default-hidden="false"]')
            .forEach(el=>el.click());
    }
}
