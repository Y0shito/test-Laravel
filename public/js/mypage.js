'use strict';

const userLogout = () => {
    if (window.confirm('ログアウトしますか？')) {
        document.logout.submit();
    } else {
        return false;
    }
}

const articleDelete = () => {
    if (window.confirm('本当に削除しますか？')) {
        document.articleOptions.submit();
    } else {
        return false;
    }
}

const unsubscribe = () => {
    if (window.confirm('本当に退会しますか？')) {
        document.userDelete.submit();
    } else {
        return false;
    }
}
