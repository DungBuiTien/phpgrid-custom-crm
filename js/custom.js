function display_PerErrMsg(){
    alert("Bạn không có quyền truy cập vào trang này.");
    history.go(-1);
}

function display_ErrMsg(err){
    alert(err);
    history.go(-1);
}