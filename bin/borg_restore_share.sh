#!/usr/bin/env bash

export BORG_RSH="ssh -i /root/.ssh/borg/borg_backup -F /root/.ssh/borg/borg_config"

BORG_REPO="backup@catrobat-backup:/mnt/md0/borg_backup/catrobat-share"
PUBLIC_FOLDER="/var/www/share/shared/public/resources"
SQL_RESTORE_FOLDER="/tmp/sqlrestore"
DEB_DEFAULT="/etc/mysql/debian.cnf"
SQL_BORG_FOLDER="borg_backup/tmp/catrobat-share"
BACKUP_ARCHIVE=$(borg list backup@catrobat-backup:/mnt/md0/borg_backup/catrobat-share --last 1 | awk '{print $1;}')

# =======================================================================================================================
# $this->executeSymfonyCommand('catrobat:purge', ['--force' => true], $this->output);
# bin/console catrobat:purge --force
# =======================================================================================================================
echo ${BACKUP_ARCHIVE}

mkdir -p "${SQL_RESTORE_FOLDER}" &&

cd "${SQL_RESTORE_FOLDER}" &&
borg extract --strip-components=3 ${BORG_REPO}::${BACKUP_ARCHIVE} ${SQL_BORG_FOLDER} &&
mysql --defaults-file=${DEB_DEFAULT} < mysqldump_all_databases.sql &&
rm mysqldump_all_databases.sql &&

cd "${PUBLIC_FOLDER}" &&
borg extract --strip-components=6 ${BORG_REPO}::${BACKUP_ARCHIVE} ${PUBLIC_FOLDER:1}

# =======================================================================================================================
# $em = $this->getContainer()->get('doctrine')->getManager();
# $query = $em->createQuery("UPDATE App\Entity\Program p SET p.apk_status = :status WHERE p.apk_status != :status");
# $query->setParameter('status', Program::APK_NONE);
# $result = $query->getSingleScalarResult();z
# //'Reset the apk status of ' . $result . ' projects'
# $query = $em->createQuery("UPDATE App\Entity\Program p SET p.directory_hash = :hash WHERE p.directory_hash != :hash");
# $query->setParameter('hash', 'null');
# $result = $query->getSingleScalarResult();
# //'Reset the directory hash of ' . $result . ' projects'
# =======================================================================================================================

