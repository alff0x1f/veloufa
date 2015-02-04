from django.db import models
from django.contrib.auth.models import User

from django.db.models.signals import pre_delete
from django.dispatch import receiver

class Albom(models.Model):
    owner = models.ForeignKey(User,)
    description = models.CharField(max_length=255, blank = True)
    def __unicode__(self):              # __str__ on Python 3
        return self.owner.__unicode__() + '/' + self.description

class Picture(models.Model):
    albom = models.ForeignKey(Albom)
    description = models.CharField(max_length=255, blank = True)
    added = models.DateField(auto_now_add = True)
    pic_1280 = models.CharField(max_length=100, default='')
    pic_600 = models.CharField(max_length=100, default='')
    pic_200 = models.CharField(max_length=100, default='')
    pic_50 = models.CharField(max_length=100, default='')

    def __unicode__(self):              # __str__ on Python 3
        return self.albom.__unicode__() + '/' + self.description

    def save_from_url(self, image_url):
        import io
        import os
        import urllib2
        import uuid
        import datetime
        from PIL import Image
        from django.conf import settings
        from django.core.exceptions import ObjectDoesNotExist

        max_size = 1024*1024*10
        try:
            resp = urllib2.urlopen(image_url, timeout = 2)
        except:
            return 'Wrong url'
        if 'content-length' in resp.headers and int(resp.headers['content-length']) > 1024*1024*10:
            return 'File bigger than %' % max_size
        elif 'content-type' in resp.headers and resp.headers['content-type'].split('/')[0] != 'image':
            return 'File is not image'
        image_str = resp.read(1024*1024*10)
        if resp.read(1) != '':
            return 'File bigger than %s' % max_size
        image_file = io.BytesIO(image_str)

        image_1280 = Image.open(image_file)
        xsize, ysize = image_1280.size
        if xsize < 50 or ysize < 50:
            return "Image size too small"
        if xsize > 10000 or ysize > 10000:
            return "Image size too big"

        #if self.albom is None:
        try:
            self.albom
        except ObjectDoesNotExist:
            user_admin = User.objects.get(id = 1)
            alboms = Albom.objects.filter(owner = user_admin, description="default")
            if alboms.count() == 0:
                new_albom = Albom()
                new_albom.owner = user_admin
                new_albom.description = 'default'
                new_albom.save()
                self.albom = new_albom
            else:
                self.albom = alboms.last()


        uuid_tmp = datetime.datetime.now().strftime('%y%m') + uuid.uuid4().bytes.encode('base64').rstrip('=\n').replace('/', '_')
        # reverse operation:  #uuid.UUID(bytes=('G+vmqOr+TqmrALjajE4uRQ' + '==').replace('_', '/').decode('base64'))

        url_uuid =  uuid_tmp[:4] + '/' + uuid_tmp[4] + '/'
        path_uuid = settings.IMG_DIR + url_uuid

        filename_uuid = uuid_tmp[5:]
        if not os.path.exists(path_uuid):
            os.makedirs(path_uuid)

        if image_1280.format == "PNG":
            save_format = "PNG"
            save_format_ext = "png"
        else:
            save_format = "JPEG"
            save_format_ext = "jpg"
            if image_1280.mode == "P":
                image_1280 = image_1280.convert('RGB')

        #resize to a image a, 1280x960
        image_1280.thumbnail( (1280, 960), Image.ANTIALIAS)
        xsize, ysize = image_1280.size

        #resize to a image b, 600x960
        if xsize > 600 or ysize > 450:
            xratio = xsize / 600.
            yratio = ysize / 450.
            if xratio > yratio:
                result_size = (600, int(round(ysize/xratio)))
            else:
                result_size = (int(round(xsize/yratio)) , 450)
            image_600 = image_1280.resize(result_size, Image.ANTIALIAS)
        else:
            image_600 = image_1280
        image_1280.save(path_uuid + filename_uuid + 'a.' + save_format_ext, save_format)
        self.pic_1280 =  url_uuid + filename_uuid + 'a.' + save_format_ext

        xsize, ysize = image_600.size
        #resize to a image c, 200x150
        if float(ysize)/float(xsize) > 0.75: #if high image
            y_hight = int(round((xsize*0.75)))
            y_start = int((ysize - y_hight)/2)
            image_200 = image_600.crop((0, y_start, xsize, y_start + y_hight))
        elif float(ysize)/float(xsize) < 0.75: #if wide image
            x_wide = int(round((ysize/0.75)))
            x_start = int((xsize - x_wide)/2)
            image_200 = image_600.crop((x_start, 0, x_start + x_wide, ysize))
        else:
            image_200 = image_600
        image_200 = image_200.resize((200,150))
        image_200.save(path_uuid + filename_uuid + 'c.' + save_format_ext, save_format)
        self.pic_200 =  url_uuid + filename_uuid + 'c.' + save_format_ext

        #resize to imade d, 50x50
        if float(ysize)/float(xsize) > 1: #if high image
            y_hight = xsize
            y_start = int((ysize - y_hight)/2)
            image_50 = image_600.crop((0, y_start, xsize, y_start + y_hight))
        elif float(ysize)/float(xsize) < 0.75: #if wide image
            x_wide = ysize
            x_start = int((xsize - x_wide)/2)
            image_50 = image_600.crop((x_start, 0, x_start + x_wide, ysize))
        else:
            image_50 = image_600
        image_50 = image_50.resize((50,50))
        image_50.save(path_uuid + filename_uuid + 'd.' + save_format_ext, save_format)
        self.pic_50 =  url_uuid + filename_uuid + 'd.' + save_format_ext

        image_600.save(path_uuid + filename_uuid + 'b.' + save_format_ext, save_format)
        self.pic_600 =  url_uuid + filename_uuid + 'b.' + save_format_ext

        self.save()
        return ''

@receiver(pre_delete, sender=Picture)
def delete_repo(sender, instance, **kwargs):
    """Delete image files when we delele model from Database"""
    import os
    from django.conf import settings
    if sender == Picture:
        for deleting_image in [instance.pic_1280, instance.pic_600, instance.pic_200, instance.pic_50]:
            try:
                os.remove(settings.IMG_DIR + deleting_image)
            except OSError:
                pass

class Userprofile(models.Model):
    user = models.OneToOneField(User)
    phpbb3_id = models.IntegerField(default=-1)
    phpbb3_pass_hash = models.CharField(max_length=50, default="")
    avatar = models.ForeignKey(Picture, blank = True, null = True)
    posts = models.IntegerField(default = 0)
    phone_number = models.CharField(max_length=50, blank = True)
    birthday = models.DateTimeField(null = True, blank = True)
    ip = models.GenericIPAddressField()
    rating = models.DecimalField(max_digits=12, decimal_places=2, default = 0)
    locality = models.CharField(max_length=100, blank = True)

    def __unicode__(self):              # __str__ on Python 3
        return self.user.__unicode__()