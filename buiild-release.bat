@echo off



echo OUHK SSC 開發人員工具





pause

jarsigner -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore chrono_app.keystore .\platforms\android\build\outputs\apk\android-release-unsigned.apk alias_name



echo 已數位簽署
set /p id="輸入版本號: "


zipalign -v 4  .\platforms\android\build\outputs\apk\android-release-unsigned.apk Chrono-Ops-Release-%id%.apk


pause