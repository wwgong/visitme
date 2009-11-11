<fb:if-section-not-added section="profile">  
	Display our box on your profile!<br />  
	<fb:add-section-button section="profile" /> 
</fb:if-section-not-added>

<center>
    <img src="http://www.umbcs.org/gopandas/visitme/images/logo.jpg" alt="logo" />

    <form action="http://apps.facebook.com/kayakme/" id="searchForm" method="post">
        Search friend: <fb:friend-selector uid="#request.userID#" name="uid" idname="friend_sel" />
        <input type="submit" value="Go!" />
    </form>

	<br/><br/>
</center>