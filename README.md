#DHDC 3.0 Plugable PM:Utehn Jadyangtone DHDC Team
<hr>
#GIT Sync
<p> (1) git -commit
<p> (2) git -remote -push หรือ git -remote -push to upstream ( ถ้า push ไม่ได้ให้ merge หรือ ทำข้อ (2.1) แล้วทำข้อ (3) )
<p> (2.1) git -remote -pull -merge
<p> (3) git -remote -push
<p>=============

<hr>
<p> For User
<p> <b>ขั้นตอนการติดตั้ง</b>
<p>(1) สร้างฐานชื่อ dhdc3 กำหนด Collation เป็น utf8_general_ci
<p>(2) ติดตั้ง dhdc3 ที่ wwwroot (git clone & composer install)
<p>(3) ตั้งค่าการเชื่อมต่อฐานข้อมูล dhdc3 ที่ common/config/connect_database.php
<p>(3.1) Restore ฐานตั้งต้น dhdc4 ที่ http://203.157.118.123:88/dhdc3_setup_416t_97p.zip
<p>(3.2) Restore ไฟล์ components/rbac/rbac_20170620.sql เข้าฐาน dhdc3
<p>(4) Login = admin ,123456
<p>(5) จัดการระบบ - ตั้งค่าอำเภอ 
<p>(6) จัดการระบบ - tranform 
<p>(7) กรณีมีฐาน 53 แฟ้ม อยู่ก่อนหน้า ให้ copy เฉพาะตาราง 53 แฟ้ม มาใส่ในฐาน dhdc3
<p>(8) จัดการระบบ - จัดการผู้ใช้ -create new user
<hr>
<p><b>Linux</b>
<p>-แก้ไข httpd.conf  ส่วน AllowOverRide  All


