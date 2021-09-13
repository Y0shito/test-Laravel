'use strict'

const delete_confirm = (e) => {
    if (window.confirm('この記事を削除しますか？')) {
        document.article_options.submit();
    }
    return false;
};

// 機能しない 名前も合っている ボタン側にdelete_confirmを充てると動く
// const user_delete = (e) => {
//     if (window.confirm('今までの記事やブックマークなどが全て削除されます。退会しますか？')) {
//         document.user_delete.submit();
//     }
//     return false;
// };
