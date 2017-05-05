<form method="post" action="billplzpost.php">
    <input type="text" name='nama' required value='Wan Zulkarnan'>
    <input type="text" name='email' required value='emailsini@ymail.com'>
    <input type="hidden" name="telefonbimbit" value="0121234567">
    <input type="hidden" name="amaun" value ="54.30">
    <!--Put deliver value as "ya" to enable notification-->
    <!--Put deliver value as "email" to enable emmail notification only-->
    <!--Put deliver value as "sms" to sms notification only-->
    <!--Leave blank for no notification-->
    <input type="hidden" name="deliver" value ="">
    <input type="hidden" name="reference_label_1" value="">
    <input type="hidden" name="reference_1" value="7">
    <input type="hidden" name="description" value ="apa2">
    <input type="hidden" name="successpath" value = "">
    <input type="hidden" name="collection_id" value = "">
    <input type="submit">
    
</form>
