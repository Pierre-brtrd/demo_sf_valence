/**
 * Class filter for search posts in ajax
 * 
 * @property {HTMLElement} pagination - The pagination element
 * @property {HTMLElement} content - The content element
 * @property {HTMLElement} sortable - The sortable element
 * @property {HTMLFormElement} form - The form element
 * @property {HTMLElement} count - The count element
 */
export default class Filter {

    /**
     * @param {HTMLElement} element - the parent element of the page of search
     */
    constructor(element) {
        if (element == null) {
            return;
        }

        this.pagination = element.querySelector('.js-filter-pagination');
        this.content = element.querySelector('.js-filter-content');
        this.sortable = element.querySelector('.js-filter-sortable');
        this.form = element.querySelector('.js-filter-form');
        this.count = element.querySelector('.js-filter-count');
        this.bindEvents();
    }

    /**
     * Add actions to the elements
     */
    bindEvents() {
        const linkClickListener = (e) => {
            // Si l'élément est une balise <a></a> OU une balise <i></i>
            if (e.target.tagName === 'A' || e.target.tagName === 'I') {
                e.preventDefault();

                let url = '';

                if (e.target.tagName === 'I') {
                    url = e.target.parentNode.parentNode.getAttribute('href');
                } else {
                    url = e.target.getAttribute('href');
                }

                this.loadUrl(url);
            }
        }

        this.sortable.addEventListener('click', (e) => {
            linkClickListener(e);
        })
    }

    loadUrl(url) { }
}