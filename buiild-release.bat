@echo off



echo OUHK SSC �}�o�H���u��





pause

jarsigner -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore chrono_app.keystore .\platforms\android\build\outputs\apk\android-release-unsigned.apk alias_name



echo �w�Ʀ�ñ�p
set /p id="��J������: "


zipalign -v 4  .\platforms\android\build\outputs\apk\android-release-unsigned.apk Chrono-Ops-Release-%id%.apk


pause