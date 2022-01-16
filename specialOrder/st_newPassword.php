<?php




$st_name= $_POST['st_name'];
$st_number=$_POST['st_number'];
$st_owner= $_POST['st_owner'];
$st_tel= $_POST['st_tel'];




?>


<form class="clasic" method="post">
    <table class="spo_tbl">
        <tr>
            <th>비밀번호</th>
            <td>
                <div>
                    <div>
                        <span class="passTxt">비밀번호</span>
                        <input type="password" name = "password" size = 20>
                    </div>
                    <div>
                        <span class="passTxt">비밀번호확인</span>
                        <input type="password" name = "password_re" size = 20>
                    </div>
                    
                </div>
            </td>
        </tr>            
    </table>
    <div class="btn-group">
        <button class="btn btn-success" type="button" onclick="location.href='./main.php'" >취소</button>
        <button class="btn btn-success" type="button" onclick="new_password()">변경</button>
    </div>
</form>



