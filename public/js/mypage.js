'use strict';

// const article_delete = () => {
//     if (window.confirm('本当に削除しますか？')) {
//         document.articleOptions.submit();
//     } else {
//         return false;
//     }
// }

const unsubscribe = () => {
    if (window.confirm('本当に退会しますか？')) {
        document.userDelete.submit();
    } else {
        return false;
    }
}
