<?php

ini_set('max_execution_time', 7200);
include_once '../Config.php';
$i = 0;
$count = 0;
$notSentEmail = array();
$mysql = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE_NAME);
if ($mysql->connect_errno > 0) {
    throw new Exception("Connection to server failed!");
} else {
    $sql = "SELECT DISTINCT (u.`id`), u.`firstname` , u.`lastname` , u.`email` , p.original FROM  `user_personal_info` AS u LEFT JOIN pictureuploads AS p ON u.id = p.user_id WHERE p.original IS NULL GROUP BY u.id";
    if (!isset($_GET['useString'])) {
        if ($result = $mysql->query($sql)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $i++;
                    $name = toSentenceCase(trim("$row[firstname] $row[lastname]"));
                    $msg = "<!doctype html><html lang='en'><head><meta charset='utf-8'><style>a:hover{color: #000;}a:active , a:focus{color: green;}.index-functions:hover{/*cursor: pointer;*/ /*color: #99C43D !important;*/ -webkit-box-shadow: inset 0px 0px 1px 1px #ddd;box-shadow: inset 0px 0px 1px 1px #ddd;}.index-functions:active{/*color: #C4953D !important;*/ -webkit-box-shadow: inset 0px 0px 1px 2px #ddd;box-shadow: inset 0px 0px 1px 2px #ddd;}/*********************************************/ </style></head><body style='font-family: 'Segoe UI',sans-serif;background-color: #f9f9f9;color: #000000;line-height: 2em;'><div style='max-width: 800px;margin: 0 auto;background-color: #fff;border: 1px solid #f2f2f2;padding: 10px'><div class='header'><img style='float: right;top: 0px;' src='http://service.gossout.com/images/gossout-logo-text-and-image-Copy.png'/><br><h2>Update Your Profile Picture</h2><p style='margin: 3px;'><span class='user-name'>Hi <a style='color: #62a70f;text-decoration: none;'>$name</a>, We realized you have not uploaded a profile picture since you joined gossout. People with photos find more friends and attract more invitations. The following steps show you how to get started: </span></p><hr style='margin: .3em 0;width: 100%;height: 1px;border-width:0;color: #ddd;background-color: #ddd;'></div><center><div class='index-intro-2' style='line-height: 20px;font-size:14px;'><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;color:#62a70f;' class='index-functions'><center>Click on the user icon indicated below, then click <em>Profile &Settings</em></center></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;color:#62a70f;' class='index-functions'><center>Click <em>Click to choose image</em> to select image of your choice. </center></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;color:#62a70f;' class='index-functions'><center>Click <em>Upload Photo</em> to get your profile picture updated! </center></div></div><div class='index-intro-2'><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><img src='http://www.gossout.com/images/a1.jpg' alt='Click on Profile & Settings' style='max-width: 200px;height: auto;'/></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><img src='http://www.gossout.com/images/a2.jpg' alt='Click on Profile & Settings'/></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><img src='http://www.gossout.com/images/a3.jpg' alt='Click on Profile & Settings'/></div></div></center><hr style='margin: .3em 0;width: 100%;height: 1px;border-width:0;color: #ddd;background-color: #ddd;'><div style='background-color: #f9f9f9;padding: 10px;font-size: .8em;'><center><div class='index-intro-2'><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><div style='margin: 0 auto;width: 24px;height:1em'><span style='margin-right: .15em;display: inline-block;width: 24px;height: 24px;'><img src='http://service.gossout.com/images/community-resize.png'/></span></div><h3 style='text-align: center;height: 1em;'>Discover</h3><p style='margin: 3px;color: #777;line-height: 1.5;margin-bottom: 1em;padding-top: 1em;font-size: .8em;padding-top: 0;'>Communities &Friends</p></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><div style='margin: 0 auto;width: 24px;height:1em'><span style='margin-right: .15em;display: inline-block;width: 24px;height: 24px;'><img src='http://service.gossout.com/images/connect-pple.png'/></span></div><h3 style='text-align: center;height: 1em;'>Connect</h3><p style='margin: 3px;color: #777;line-height: 1.5;margin-bottom: 1em;padding-top: 1em;font-size: .8em;padding-top: 0;'>Meet People, Share Interests</p></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><div style='margin: 0 auto;width: 24px;height:1em'><span style='margin-right: .15em;display: inline-block;width: 24px;height: 24px;'><img src='http://service.gossout.com/images/search.png'/></span></div><h3 style='text-align: center;height: 1em;'>Search</h3><p style='margin: 3px;color: #777;line-height: 1.5;margin-bottom: 1em;padding-top: 1em;font-size: .8em;padding-top: 0;'>Communities, People and Posts</p></div></div></center><hr style='margin: .3em 0;width: 100%;height: 1px;border-width:0;color: #ddd;background-color: #ddd;'><table cellspacing='5px'><tr ><td colspan='3'><a style='color: #62a70f;text-decoration: none;' href='http://www.gossout.com'>&copy;" . date('Y') . " Gossout</a></td></tr></table></div></div></body></html>";
                    $to = "$name<$row[email]>";

                    $subject = "Update Your Profile Picture";
                    $headers = "From: Your Profile<no-reply@gossout.com>\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $res = @mail($to, $subject, $msg, $headers);
                    if ($res) {
                        $count++;
                    } else {
                        $notSentEmail[] = htmlentities($to);
                    }
                    echo htmlentities($to) . "<br/>";
                }
            } else {
                echo $mysql->error;
                exit;
            }
        }
    } else {
        $arr = json_decode('["ABDULLAHI LAWAL<smallsparter@yahoo.com>","Haroun Xagreat<xagreat@ovi.com>","Ibk Ogundola<Ibkdablackone@yahoo.com>","Danny Acre<Codedambitnz@gmail.com>","Hassan Maryam<hmaryam47@rocketmail.com>","Abutahir Umar<abutahir.umar@yahoo.com>","Lubem Gberikon<lubcheez@gmail.com>","Chris Brunt<www.flexyzyoung@yahoo.com>","Opeoluwa Adeleke<ayanlajaopeoluwaade@yahoo.com>","Grace Onu<onugraceegbi@yahoo.com>","Samaila Sammani<ambassadorialmc@yahoo.com>","Auwal Habib<auwalhabib98@yahoo.com>","Tunde Onifa<onifadeakin@yahoo.com>","Njina Joel<njinajoel@gmail.com>","Splendid Jarem<teeyboy@ymail.com>","Abdulkareem Akeem<akspezia@yahoo.com>","Oluwatobi Oyewale<tobilolaoyewale@gmail.com>","Wusu Bolaji<bolergy4me@yahoo.com>","Gttfd Ghhh<dwsef@yahoo.com>","Dennis Shola<denins4us@gmail.com>","Bunmi Adeyemi<hezzbyte@gmail.com>","Ibrahim Bello<ibbello2003@yahoo.com>","Father Mother<mcmil3z@yahoo.com>","Umar Abdullahi<abebe4mother@yahoo.com>","Ayoola Oriola<Ayoolaoriola@yahoo.com>","Ola Olu<jigherfever@gmail.com>","Ismael Ridwan<Dagamaridwan@yahoo.com>","Fritzzle Dee<Fuad.salami2010@yahoo.com>","Muawiya Yaroson<Yusufyarosonmuawiya@gmail.com>","Sekynat Abbey<spiffy.angel@yahoo.com>","Abogunloko Holluwadollarpor<abogunlokorasheedat@yahoo.com>","Gbams Ayoola<gbemisam2007@yahoo.com>","Auwal Balarabe Adaji<auwalbalarabeadaji@yahoo.com>","FREEDOM TERKUMA<FREEDOMNYOUGH@YAHOO.COM>","Trice Makavelli<khaleefahadams90@gmail.com>","Kamal Muhammad<muhammadkamal131@yahoo.com>","Salisu Abubakar<salisuabubakar60@yahoo.com>","Muhammad Yusuf Musa<mymusa2k8@yahoo.com>","Sholly Dolla<alamide@rocketmail.com>","Emeka Mbanefo<emeksmbanefo@yahoo.co.uk>","Flesh N Bone<biobukar01@yahoo.com>","Ahmad Junaid<Silicon006@yahoo.com>","Abubakar Lawal<absadeeqlawal@yahoo.com>","JEHMYS HORLA9YY<olaniyi.joshua@ymail.com>","Muhammed Abdullah<abdullahmuhammed48@yahoo.com>","Muhammad MD<triplecmd@rocketmail.com>","Odufejo Kemmy<kodufejoade@yahoo.com>","Idrees Abubakar<idrisabubakar381@yahoo.com>","Khalifa B.adaji<khalifabalarabeadaji@yahoo.com>","Musa Abdullahi<spendoe12@yahoo.com>","Bash Sheezie<bash7272@yahoo.com>","Usman Oyeleke<usman_oyeleke@yahoo.com>","Ogundeyi Oluwadamilare<ogundeyioluwadamilare@gmail.com>",null,"Ogunbiyi Abiola<ogunbiyiabiola92@yahoo.com>","Beatrice Cameron-Cole<bcameroncole@yahoo.com>","Hassan Muhammad<hassan2000online@yahoo.com>","Smorl Alcarly<simrakool@yahoo.co.uk>","Abayomi Abiodun<abeeylomo@yahoo.com>","Musa Abdullahi<amusa67@rocketmail.com>","Ahmad Sani Jhn<ahmadj008@yahoo.co.uk>","Olaribigbe Habeebat<Olaribigbehabeebat@gmail.com>","Kabiru Musa<kabiru67@yahoo.com>","Idoko Dave<idokodavids@yahoo.com>","Aldreyna C-west<sobywest@gmail.com>","Ayamolowo Ayobami<da.blink2011@yahoo.com>","Adigun Temitope<Adiguntemitope123@yahoo.com>","Olarinoye Oluwafemi<olarinoyeoluwafemi@gmail.com>","Adio Kabiru<adkasu26@gmail.com>","Victor Adeoye<Kollywals@gmail.com>","Dosunmu Farouk<Olamount@gmail.com>","Olawuyi Lateef<Olawuyijohn94@yahoo.com>","Adeboye Temidayo S.<dy.dx4maths@gmail.com>","Muh\'d Bello<Slymmd@gmail.com>","Musa Yusuf<talk2farmer@yahoo.com>","Bala Esther<estherba99@yahoo.com>","Adex Dave<harbarmy00@gmail.com>","Ahmeen Ridwan<ahmeen.ridwan@yahoo.com>","Hollushawlar Steven<steve2excel@yahoo.com>","Junior Etubi<j_boydmayor@yahoo.com>","Mike Obi<obiskomike2@yahoo.com>","Pharty Haroun<Www.fatima@rocketmail.com>","Lawal Eniola<maryannelawal@gmail.com>","Osonaike Adeniyi<osonaikea@yahoo.com>","Alh Qarami<awwalbaba@gmail.com>","Idris Bashiru<bashaloma@yahoo.com>","Musa Bahly<musabala86@yahoo.com>","Oduyemi Olabode<famajibo@yahoo.com>","Aboutarik Issa<aboutarik@yahoo.fr>","Oladipo Moses Temityo<idptayo@yahoo.com>","Hamza Ibrahim<Ibrahimhm01@gmail.com>","Yasir Yakub<Yasiryakub63@yahoo.Com>","Xtar Kay<xtarkay@yahoo.com>","Dammy Davies<Damiemiabata@yahoo.com>","Hfduyvb Jgd<tkolo54@yahoo.com>","Marie-lois Okoh<malioo4eva@yahoo.com>","Shamsu Muhaammed<shamsumuhammedrumah00647@gmail.com>","Obikoya Gabriel<gabrealina94@ymail.com>","Isyaku Mukhtar<isyaku5180@ovi.com>","Musa Moshood<musamoshood@gmail.com>","Napheysir Harun<Naphey@ovi.com>","Shamsu Muhammedrumah<shamsumuhammedrumah@yahoo.com>","Candice_whyarles Oladejo<gwhyarles@yahoo.com>","Ummahani Aliyu<Fibrahim224@gmail.com>","Auwalu Abdullahi<auwaluabdullahi64@gmail.com>","Blis Zubziro<Bliszubziro@yahoo.Com>","Adamu Abdullahi<adamdandume@yahoo.com>","Joseph Wumi Oladapo<Josewumi@yahoo.com>","Saidu Naabba<saidm.naabba@yahoo.com>","Asheer Ozil<ashbadees@yahoo.com>","Abbah King<tewezy@yahoo.com>","Mus\'ab El-justice<Ameeryau@gmail.com>","Aisha Yahya<aisha_dada@yahoo.com>","Jeboarps Muhammed<www.mbello771@gmail.com>","Al Ameen Abdullahi<www.alaminabdullahi58@yahoo.com>","Nana-ameen Nanatu<Habibadahiru@ymail.com>","Talatu Kwasu<trip4tk@gmail.com>","Al Ameen Abdullahi<alaminabdullahi58@yahoo.com>","Joy Dakok<joydakok@yahoo.com>","Tordue Tony<ishuhtordue@gmail.com>","Basheer Naseer Naseer<nasirb47@yahoo.com>","James Unuatase<jamesunuatase@gmail.com>","Samson Ayodeji<sunday.johnson@ymail.com>","Humairah Ibrahim<Humyluv@gmail.com>","Wakiii Isah<wiromi867@gmail.com>","Glodean Reet<amakaO17@yahoo.com>","Zainy Zeeee<getfinancialloan@gmail.com>","Sulayman Kabir<ksmahuta1@yahoo.com>","Alameen Haruna<Aminuabdullahi62@yahoo.Com>","Aisha Idowu<aisha.idowu@yahoo.com>","Habeeb Ope<Habeebope@ymail.Com>","Ultrarich Guy<easy4niger@yahoo.com>","Basheer Muhammad<bashirfrg1@yahoo.com>","MUKHTAR JUNAIDU<mukhtarjunaidu@hotmail.com>","James Ejembi<Jamesejembiworld@yahoo.com>","Maikano Duniya<maikanoamosduniya@yahoo.com>","ABBA SARKEE<ABBASARKEE@gossout.com>","Ahmed Adamu<Ahmedadam990@rocketmail.com>","Amina Mustapha<amsylyte@yahoo.com>","Oduola Yinka<Ykga_john@yahoo.com>","Michael Olabode<Olabodetobi86@yahoo.com>","Ganex Up Ganex<Oladokunganiu@yahoo.com>","OLADUGBA OLAJUMOKE<mitshellpinkey@gmail.com>","MEERAH OTHMAAN<AMEERAFAROUQ@YAHOO.COM>","Emmanuel Okafor<nondefyde@gmail.com>","Xara Yusuf<xarayusuf96@yahoo.com>","Mustapha Sheikh<m.sheikh4lyf@yahoo.com>","Okafor Martins<exceptionalchike@yahoo.com>","Saleem Malami<saleem.jabeer@yahoo.com>","Mustapha Bello<mustyblackmajor92@gmail.com>","Gregory Otonoh<gregotonoh@yahoo.com>","Francis Francis<uafran6@yahoo.com>","Tobi Loba<Sinaayomi4jah@yahoo.com>","Oluwasina Seyi<seyibeyonce@yahoo.com>","John Adaramaja<oluwatobijohn911@gmail.com>","Mahdi Danjuma Sani<mahdidanjuma@gmail.com>","Onasanya Oladapo<onasanyaoladapo@yahoo.com>","Ibrahim Lefteer<Lefteer@gmail.com>","Abdullateef Abdulsalam<abcubed3@gmail.com>","Sodamola Damilola<sodam001@rocketmail.com>","Areje Banji<olabanji27@gmail.com>","Benny Yossi<danjumayosi@yahoo.com>","Obideyi Joseph<king4increase@yahoo.com>","Olivia Presh<okparaolivia@gmail.com>","Victor Ita<victor_ita@yahoo.com>","Olarike Awofisayo<rickeyshow@yahoo.com>","Cloud Magaji<victordauda@gmail.com>","Oki Gift<Dopikk@yahoo.com>","Daniel Micheal<lilzane_4u@yahoo.com>","Somkene Olioni<sokyoliobi@gmail.com>","Naseeru Taneemu Annuree<taskarnta@gmail.com>","Martins Chinwike Okafor<chikemore@gmail.com>","ME MYSELF<jaytothabee@yahoo.co.uk>","Ibraky Ky<ibraky@yahoo.com>","Yusuf Kabir<ykabir40@hotmail.com>","Mexis Sly<tamsmith123@yahoo.com>","Emmanuel Okafor<nondefide@yahoo.com>","Baseera Yusuf<ybaseera@gmail.com>","Kabeer Dauda<kabirdauda32@yahoo.com>","YAMAN AMINU<yamanaminu@gmail.com>","Sani Sale<sani.gaya@yahoo.com>","Haruna Tobiloba<arunatobi@yahoo.com>","C.h Sule<get2sule@yahoo.com>","Jennifer Onwunali<djnicolas@yahoo.com>","Romario Da-silva<macquissilva72@yahoo.com>","Muhammad Kabo<Jamilkabo@gmail.com>","Abubakar Lele<sadiqsalehahmad@gmail.com>","MARYAM SULEIMAN<MARYAMSAJUMAHPLAZA@YAHOO.COM>","Seye Fafunso<Seyefafee@yahoo.com>","Romario Dasilva<macquissilva72@yahoo.ie>","Suweibat Suleiman<ssuweibat@yahoo.com>","Kersha Irun<debola.f@gmail.com>","Asmo Don<asmothedon1@gmail.com>","Judd Due<duejudd@yahoo.com>","Ruky Abdul<arukayya@ymail.com>","Olamide Odumade<Olamide.adetu@yahoo.com>","Yangs Gyoreng<balagsy@yahoo.com>","Joyce Yashim<joyceyashim@yahoo.com>","Phil George<danjumaphil90@yahoo.com>","Sasilka Malgwi<sandysoil_chick@yahoo.com>","Isah Ismail<Isahismaili@yahoo.com>","Abdulsalam Aminu<ameent2@yahoo.com>","Yusuf Abubakar<yusufabubakarhitman47@yahoo.com>","Darius Jerome<Jeromedarius@nokiamail.com>","Princee Abdul<Princelatifa@ymail.com>","Abdul Sayid<abdulsayid21@yahoo.com>","Daniel Agada<Danielagada@nokiamail.com>","Xee Lilware<meenaalilwar@yahoo.com>","IFEDILI OLIOBI<ifoliobis@yahoo.com>","Oyelakin Racheal<rachyjay2009@gmail.com>","Yetunde Kabeerah<Osoluvska@yahoo.com>","ABDULMALIK FAROUK<pharouqq@facebook.com>","Deaszy Desire<danlamiaminu@ymail.com>","Ibrahim Nasir<Kh4l33l@yahoo.com>","Zainab Olayinka Ajayi<ajayizainab@gmail.com>","Olugbode Ifeoluwa<lomo4luv427@yahoo.com>","HUEY SADIQ<1sadiq@live.com>","Stewart Ross<mail2cole@yahoo.com>","Samsonho Samsonho<Jazzcmajor@gmail.com>","ALIYU MAHDI<aliyumahdi200@gmail.com>","Mohammed Nma Sulyman<Sulexjtb@yahoo.com>","Zainab Umar<Swtglitz@gmail.com>","Umaru Ali<umargajo2002ng@yahoo.com>","Omowumi Bottoh<lizzodunlami@yohoo.com>","Ayomide Ayodele<ifejuwuralo@yahoo.com>","Okonkwo Arinze<okonkwoarinze99@yahoo.com>","Ruby Gold<anuforolove@ymail.com>","Mary Smith<marysmith@yahoo.com>","Kevin Ndams<keezy00@gmail.com>","Abdulmajeed Adesina<Abdulmajeedadesina@yahoo.com>","Leykhon Ruphai<leiksiano@yahoo.com>","Olaribigbe Habeebat<Olaribigbehabeebat@yahoo.com>","Erugo Robert<slimbobby4real@yahoo.com>","Abubakar Sadeeq Alkali<sadiq4c_in_c@yahoo.com>","Ademayowa Adeniyi<mayowaadeniji@yahoo.com>","Atta Ibrahim<attaibrahim@ymail.com>","AHMED RIKO EHRAB<ricqo4real@yahoo.com>","Joseph Onotu<mailnabronco@gmail.com>","Mansur Salisu<msay4makenzy@gmail.com>","Bako Bebeji<ibrahimbebeji@yahoo.com>","Phillips Sandra<dannlixz@yahoo.com>","Coded Royalty<asegunadeyemi@yahoo.com>","Ibrahim Usman<ibsmart2008@yahoo.com>","IDRIS IDAH<idris4realat007@gmail.com>","Tijjani Ahmad<tijjani.ahmad15@gmail.com>","Maina Suleiman<suleimaina@gmail.com>","Motown Philly<www.romeonyou@yahoo.com>","Motowns Philly<romeonyou@yahoo.com>","Ismail Bashir<microbashes@yahoo.com>","Bello Abdulkabeer<kabeer4bello@yahoo.com>","Emmanuel Okafor<nondefyde@yahoo.com>","Jeremiah Adamson<jaletechs@gmail.com>","Kamilah Aliyu<cameela@ymail.com>","Abdulhakim Abubakar<shamassy@yahoo.com>","Mariam Jalloh<prettymariam4life@yahoo.co.uk>","David Bottoh<davidbottoh@yahoo.com>","Okhuoya Victor<Vickstickz@yahoo.com>","Taylor Hickman<a.andrew02@yahoo.com>","Jimoh Sheriff<shefjam@yahoo.com>","Olaitan Bamidele<bamidele_olaitan@yahoo.com>","Elis Beth<elisbethodunlami@yahoo.com>","<ainaomotayo@ymail.com>","Kashim Kyari<kashimkyari@gmail.com>","Hunter Ogbala<ogbakaonline@yahoo.com>","Balogun Opeyemi<harrydonald411@gmail.com>","Ikechukwu Collins<collinkikechukwu@gmail.com>","Lionel Pascal<successfulpasz@yahoo.com>","Azubuike Chibuzor<mirianazubuike@gmail.com>","Felix Okafor<felixokafor46@yahoo.com>","Danjuma Muben<muben4love@yahoo.com>","Halima Audu<Halima@yahoo.com>","COLLINS TOCHUKWU<ogunwacollins@gmail.com>","Wale Dedoyin<whalay02@gmail.com>","UBANWA EVONGWA<ubanwaevongwa@yahoo.com>","Olamilekan Keji<suleimono@yahoo.com>","Hamza Shuaibu<hamzymuslim@yahoo.com>","Oluwaseyifunmi Oluwafunbi<oluwaseyifunmi19@yahoo.com>","Adaeze Obianyimuo<alltozella@gmail.com>","Hans David-Ajayi<hansajayi23@gmail.com>","Muftau Olaoye<miftadean@yahoo.com>","Abdulmalik Salisu<almaliksalis@yahoo.com>","Idris Kubau<babannafisa@gmail.com>","Aminu Isah Abubakar<aminuisah23@yahoo.com>","Attahiru Isa<sokoto@zoho.com>","AMIRU LAWAL BALARABE<alawalbalarabe@rocketmail.com>","Mohammed Goga<mohgoga@gmail.com>","Aliyu Aminu<aminubabakano@yahoo.com>","Mubarak Alhassan Garba Wala<Hanifagarbawala@yahoo.com>","Ibrahim Babuga<Ibuga3@yahoo.com>","Rajsu Toranke<Surajorabilu@yahoo.com>","Williams Akabueze<Graceakabueze@yahoo.com>","Esiri Ejiro<ejyroesiri@gmail.com>","Faisal Sani<fase4all007@ymail.com>","Egbuga Peterpaul<Peterpaul76@ovi.com>","Olawale Junaid<junaidyusuf47@yahoo.com>","Parklins Ifeanyichukwu<louispaclyns@yahoo.com>","James Ifediata<Ifediatajames@gmail.com>","Olabisi Akinbinu<olabisiakinbinu@gmail.com>","Surdmond Sawdeeq<sodiqagboola@yahoo.com>","Ugbabe Choco<u.choco_7@hotmail.com>","Ummi Aliyu<Aliyuzaina@gmail.com>","Funmilade Adefunmike<adefunmikeluv10@yahoo.com>","Odufejo Kemmy<oluwaseunodufejo@yahoo.com>","Bala Esther Tende<estherbala99@yahoo.com>","Adebisi Oluwaseyi<scarpod2002@gmail.com>","Shamsu A Musa<alamisiyya@yahoo.com>","Sunusi Ado<www.sunusiado99@yahoo.com>","Safeeya Ameera<ahmedsafiya22@yahoo.com>","MAGAJI DANBALA KALEKU<all4atee@yahoo.com>","B.small09 09.Bsmall<Musabashirkry@Gmail.com>","Dalhat Mohd Auwal<auwaldalhatumohd@yahoo.com>","Muhusin Abubakar<abubakarmuhusin@gmail.com>","Hbaeeb Muhammed<ohmeiza@yahoo.com>","Abbakar Jamilu<abjringim@yahoo.com>","Ray Olayemi<olaray@yahoo.com>","Ummi Ribadu<hauwauribadu@yahoo.co.uk>","Victor Shogo<Vickkyshow@gmail.Com>","NGADI LENNON<turnnelj@gmail.com>","Elizabeth Gbenle<gbenleolukorede@gmail.com>","Kamaldeen Matty<Kmatty63@gmail.com>","Abubakar Labaran Tafeeda<altafeeda@yahoo.com>","Islam Abu<zashnu09@gmail.com>","Auwal Isah Inzamoney<auwal_isah81@yahoo.com>","Mankebs Kiyawa<KB200MN@NOKIAMAIL.COM>","Okegbenro Leksyboy<Okegbenrotaofeek@gmail.com>","Alameen Ibrahim<ifl300@gmail.com>","Abubakar Bello<sadibellox90@gmail.com>","Kabir Adam<pedigreeworld@gmail.com>","Aisha Ajiya<aishaajiya@rocketmail.com>","Ahmad Ahmad<ahmad34786@gmail.com>","SEXY LIZZY<LIZZYODUNLAMI@yahoo.com>","Dije Balangu<deezahayat@yahoo.com>","Aisha Abdul<ummie11@yahoo.com>","Samuel O<hiddentreasure2k5@yahoo.com>","Ismail Yunus<ismailyunus127@gmail.com>","Michael Sani<mike4all08@yahoo.com>","Nuhu Babangida<sulaimannuhu13@yahoo.com>","Marc Austin<martoshop@hotmail.com>","Maimuna Sani<maimunahot@gmail.com>","George Owolabi<funtasticgeorginson@yahoo.com>","Eventus Agwu - Idam<eventusagwu@hotmail.com>","Tombari Akpe<akpe26k@yahoo.com>","Muhd Khairi<muhdkhairi60@yahoo.com>","Kasey Ajayi<suraaleatherworks@yahoo.com>","Habeeb Muhammed<famous2_4u@yahoo.com>","Xtar Kay<xtarkay@nokiamail.com>","Sirlowdean Chereif<firehs63@yahoo.com>","Mayowa Gloria<mayowaakinsemoyin@yahoo.com>","Faizu Garba Fagge<Faizugarbaahmad@gmail.com>","Danidion Dany<samueldaniel22@yahoo.com>","ADEDIRAN RAPHEAL<ralphcrown@yahoo.co.uk>","Slye D Short<Slyedshort@gmail.com>","Tope Daramola<www.tope.daramola.79@facebook.com>","Segun Omotosho<Shegsman100@yahoo.com>","Danny Phantom<danielsunday88@yahoo.com>","Rofiat Sukura<Omotayoadedoyin@yahoo.com>","Abdul Zaibash<Abdulzaibash@ovi.com>","Reallie Wayne<realliewayne99@yahoo.com>","Shuqqieh Shuqqieh<Pac329@nokiamail.com>","Emmanuel Ezugwu<ynotmekuz@yahoo.com>","Shola Ewa<ewawunmi@yahoo.com>","Blessed Nwa<isiugbosunday@yahoo.com>","Ahmad Ibrahim<Didat80@yahoo.com>","Oluwaseun Dj Sukky<b2bwwllp@gmail.com>","Umeh Joshua<umehnamejoshua@yahoo.com>","Othuke Ogwara<y2k.com01@yahoo.com>","Williams Punn<michealsuper111@hotmail.com>","Immaculeta Charles<Immaculetacharles@gmail.com>","Esther Ededghogho<odogbeesther@yahoo.com>","Anas Ibrahim<maleekbinanas@yahoo.com>","Ali Yusuf<alibabaomoba@yahoo.com>","Abdulhakeem Ibrahim<abdulhakeebrhm@gmail.com>","Davidalbert Albert<davidalby@ymail.com>","Jerrymartins Ugoji<jerrymartinsu@yahoo.com>","Oni Akinwunmi<akinoni56@yahoo.com>","Omo Afa<nuhuyahaya4luv@yahoo.com>","Abdul Mk<Abdullahi.hamza21@yahoo.com>","Miko Nkem<Marvinifeanyi2@rocketmail.com>"]', TRUE);
        
        foreach ($arr as $x) {
            $i++;
            $val = explode('<', $x);
            $name = $val[0];
//            echo "<br/>";
            $msg = "<!doctype html><html lang='en'><head><meta charset='utf-8'><style>a:hover{color: #000;}a:active , a:focus{color: green;}.index-functions:hover{/*cursor: pointer;*/ /*color: #99C43D !important;*/ -webkit-box-shadow: inset 0px 0px 1px 1px #ddd;box-shadow: inset 0px 0px 1px 1px #ddd;}.index-functions:active{/*color: #C4953D !important;*/ -webkit-box-shadow: inset 0px 0px 1px 2px #ddd;box-shadow: inset 0px 0px 1px 2px #ddd;}/*********************************************/ </style></head><body style='font-family: 'Segoe UI',sans-serif;background-color: #f9f9f9;color: #000000;line-height: 2em;'><div style='max-width: 800px;margin: 0 auto;background-color: #fff;border: 1px solid #f2f2f2;padding: 10px'><div class='header'><img style='float: right;top: 0px;' src='http://service.gossout.com/images/gossout-logo-text-and-image-Copy.png'/><br><h2>Update Your Profile Picture</h2><p style='margin: 3px;'><span class='user-name'>Hi <a style='color: #62a70f;text-decoration: none;'>$name</a>, We realized you have not uploaded a profile picture since you joined gossout. People with photos find more friends and attract more invitations. The following steps show you how to get started: </span></p><hr style='margin: .3em 0;width: 100%;height: 1px;border-width:0;color: #ddd;background-color: #ddd;'></div><center><div class='index-intro-2' style='line-height: 20px;font-size:14px;'><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;color:#62a70f;' class='index-functions'><center>Click on the user icon indicated below, then click <em>Profile &Settings</em></center></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;color:#62a70f;' class='index-functions'><center>Click <em>Click to choose image</em> to select image of your choice. </center></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;color:#62a70f;' class='index-functions'><center>Click <em>Upload Photo</em> to get your profile picture updated! </center></div></div><div class='index-intro-2'><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><img src='http://www.gossout.com/images/a1.jpg' alt='Click on Profile & Settings' style='max-width: 200px;height: auto;'/></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><img src='http://www.gossout.com/images/a2.jpg' alt='Click on Profile & Settings'/></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><img src='http://www.gossout.com/images/a3.jpg' alt='Click on Profile & Settings'/></div></div></center><hr style='margin: .3em 0;width: 100%;height: 1px;border-width:0;color: #ddd;background-color: #ddd;'><div style='background-color: #f9f9f9;padding: 10px;font-size: .8em;'><center><div class='index-intro-2'><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><div style='margin: 0 auto;width: 24px;height:1em'><span style='margin-right: .15em;display: inline-block;width: 24px;height: 24px;'><img src='http://service.gossout.com/images/community-resize.png'/></span></div><h3 style='text-align: center;height: 1em;'>Discover</h3><p style='margin: 3px;color: #777;line-height: 1.5;margin-bottom: 1em;padding-top: 1em;font-size: .8em;padding-top: 0;'>Communities &Friends</p></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><div style='margin: 0 auto;width: 24px;height:1em'><span style='margin-right: .15em;display: inline-block;width: 24px;height: 24px;'><img src='http://service.gossout.com/images/connect-pple.png'/></span></div><h3 style='text-align: center;height: 1em;'>Connect</h3><p style='margin: 3px;color: #777;line-height: 1.5;margin-bottom: 1em;padding-top: 1em;font-size: .8em;padding-top: 0;'>Meet People, Share Interests</p></div><div style='display: block;display: inline-block;padding: 1em;max-width: 200px;' class='index-functions'><div style='margin: 0 auto;width: 24px;height:1em'><span style='margin-right: .15em;display: inline-block;width: 24px;height: 24px;'><img src='http://service.gossout.com/images/search.png'/></span></div><h3 style='text-align: center;height: 1em;'>Search</h3><p style='margin: 3px;color: #777;line-height: 1.5;margin-bottom: 1em;padding-top: 1em;font-size: .8em;padding-top: 0;'>Communities, People and Posts</p></div></div></center><hr style='margin: .3em 0;width: 100%;height: 1px;border-width:0;color: #ddd;background-color: #ddd;'><table cellspacing='5px'><tr ><td colspan='3'><a style='color: #62a70f;text-decoration: none;' href='http://www.gossout.com'>&copy;" . date('Y') . " Gossout</a></td></tr></table></div></div></body></html>";
//
            $subject = "Update Your Profile Picture";
            $headers = "From: Your Profile<no-reply@gossout.com>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//            $x = "soladnet@gmail.com";
//                if ($row[email] == "soladnet@gmail.com") {
            $res = mail($x, $subject, $msg, $headers);
            if ($res) {
                $count++;
            } else {
                $notSentEmail[] = htmlentities($x);
            }
        }
        echo count($arr);
        echo "<br/>";
    }
}
echo "Total Email match $i<br/>Total Email sent $count";
if (count($notSentEmail) > 0) {
    echo "<br/><br/>==================Unsent mails===========================<br/>";
    echo json_encode($notSentEmail);
}

function toSentenceCase($str) {
    $arr = explode(' ', $str);
    $exp = array();
    foreach ($arr as $x) {
        if (strtolower($x) == "of") {
            $exp[] = strtolower($x);
        } else {
            if (strlen($x) > 0) {
                $exp[] = strtoupper($x[0]) . substr($x, 1);
            } else {
                $exp[] = strtoupper($x);
            }
        }
    }
    return implode(' ', $exp);
}

?>
