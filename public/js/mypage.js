'use strict';

const article_delete = (e => {
    if (window.confirm('本当に削除しますか？')) {
        document.submit();
    } else {
        return false;
    }
})
