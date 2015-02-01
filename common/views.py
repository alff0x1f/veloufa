from django.shortcuts import render

def home(request):
    user_id = request.COOKIES.get('phpbb3_8ioea_u')
    return render(request, 'common/home.html', {'user_id': user_id})
