from django.contrib import admin
from common.models import Userprofile
from common.models import Albom
from common.models import Picture

class Userprofile_admin(admin.ModelAdmin):
    fields = ['user', 'phpbb3_id']
    list_display = ('phpbb3_id')

admin.site.register(Userprofile)
admin.site.register(Albom)
admin.site.register(Picture)
