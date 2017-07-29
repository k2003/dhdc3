#DHDC 3.0 Plugable PM:Utehn Jadyangtone DHDC Team
<hr>
<p> Install
<p> <b>ขั้นตอนการติดตั้ง</b>
<p>(1) สร้างฐานชื่อ dhdc3 กำหนด Collation เป็น utf8_general_ci
<p>(2) แตกไฟล์ dhdc3 ที่ wwwroot 
<p>(3) ตั้งค่าการเชื่อมต่อฐานข้อมูล dhdc3 ที่ common/config/connect_database.php
<p>(4) Restore ฐานข้อมูลตั้งต้น เลขตามหมายเลข
<p>(5) กรณีมีฐาน 53 แฟ้ม อยู่ก่อนหน้า ให้ copy เฉพาะตาราง 53 แฟ้ม มาใส่ในฐาน dhdc3
<p>(6) Login = admin ,123456
<p>(6) จัดการระบบ - ตั้งค่าอำเภอ 
<p>(7) จัดการระบบ - จัดการผู้ใช้ - create new user
<p>(8) จัดการระบบ - ประมวลผล กดปุ่ม 1 และ 2
<p>(9) Linux OS แก้ไข httpd.conf  ส่วน AllowOverRide  All

<hr>
#GIT Sync
<p> (1) git -commit
<p> (2) git -remote -push หรือ git -remote -push to upstream ( ถ้า push ไม่ได้ให้ merge หรือ ทำข้อ (2.1) แล้วทำข้อ (3) )
<p> (2.1) git -remote -pull -merge
<p> (3) git -remote -push
<p>=============


