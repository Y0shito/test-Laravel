'use strict'

window.addEventListener('beforeunload', (event) => {
    event.preventDefault();
    event.returnValue = '';
});
