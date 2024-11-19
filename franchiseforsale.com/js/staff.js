function setStaffModal(name){
    var staffArray = [];
    // staffArray["Angie Arnold"] = {
    //     "img" : "/images/team/aarnold.jpg",
    //     "title" : "Sales Coordinator",
    //     "content" : "Angie joined Franchise Opportunities Network as Sales Coordinator in 2015. Before joining the Franchise Opportunities Network family, Angie was an Event Coordinator in Kansas City, MO. Angie takes pride in assisting to make Franchise Opportunities successful and working with a passionate team. In her free time, Angie enjoys spending time with her two shih tzus, hiking, cooking, making new friends, live entertainment and cheering on her hometown team (the Kansas City Royals!)."
    // };
    staffArray["Gary Blanchard"] = {
        "img" : "/images/team/gblanchard.jpg",
        "title" : "Experienced Developer",
        "content" : "Gary joined the Franchise Opportunities Network Team in December of 2014. He is an associate developer building a portfolio in front end web development and web applications. Prior to joining the team, he worked in high end home construction and before that served four years in the United States Marine Corps. Gary graduated Summa Cum Laude with a degree in graphics and web development from the Art Institute of Atlanta. He enjoys art, science and building things with his hands."
    };
    staffArray["Jason Hightower"] = {
        "img" : "/images/team/jhightower.jpg",
        "title" : "Graphic Designer",
        "content" : "Jason joined Franchise Opportunities Network in 2005 as Graphic Designer. He is responsible for graphic design for client web advertisement, email marketing, as well as all in-house design. Prior to joining Franchise Opportunities Network he was the graphic designer for The Document Source at Turner Broadcasting Systems in Atlanta, GA. In his spare time he is an professional artist. He enjoys live music events and art shows."
    };
    staffArray["Jerry Young"] = {
        "img" : "/images/team/jyoung.jpg",
        "title" : "Senior Account Executive",
        "content" : "Jerry has been with the Franchise Opportunities Network since 2004 and has nearly two decades of experience in sales and account management. Before joining the Franchise Opportunities Network team, Jerry worked with a small real estate and manufactured home company in Valdosta, GA. Jerry received his Bachelor's degree from Valdosta State University, majoring in Political Science. Jerry takes pleasure in assisting franchises and business opportunities achieve their expansion goals through lead generation, working out, playing golf and spending time with his family and friends."
    };
    staffArray["Lee Payne"] = {
        "img" : "/images/team/lpayne.jpg",
        "title" : "Senior Account Executive",
        "content" : "Lee joined the Franchise Opportunities Network team in early 2005, after working as a Financial Analyst for a Georgia based, nationally franchised restaurant group. Lee graduated from Valdosta State University with a degree in Business Management. His favorite part of the job is building the relationships and working closely with his clients to make sure each campaign is tailor-made to their needs to maximize the return. Lee enjoys the outdoors (hunting and fishing), and travels the country playing professional softball. Member of the Men's National team (Team USA) in 2015."
    };
    staffArray["Mary-Beth Tedder"] = {
        "img" : "/images/team/mtedder.jpg",
        "title" : "Broker Member Account Representative",
        "content" : "Mary-Beth Tedder has been with BusinessBroker.net for 9 years and has over two decades of experience in sales, customer service, and account management. Mary-Beth assists our business broker members with problem resolution, customer support, account management and also with marketing our services. Prior to joining BusinessBroker.net, Mary-Beth worked for Fortune 500 companies including Apple Computer and Pinkerton. She is a cum laude graduate of Green Mountain College and holds a Georgia Real Estate salesperson license. In her spare time, she enjoys fitness activities, art shows and is a supporter of Angels Among Us and other pet rescue organizations."
    };
    staffArray["Matt Maxwell"] = {
        "img" : "/images/team/mmaxwell.jpg",
        "title" : "General Manager",
        "content" : "Matt joined Business Broker Network as General Manager in 2007 and was promoted to GM of Franchise Opportunities Network in late 2012. Matt grew up in Norfolk, VA and graduated from the University of Virgina and also has his MBA from William & Mary. Matt worked as a City Planner for 8 years after graduating from UVA. Matt joined Dominion Enterprises in 2000 and has been in General Management roles since that time. Matt enjoys mountain biking, road biking, golf, hiking - basically anything outdoors!"
    };
    staffArray["Travis Cook"] = {
        "img" : "/images/team/tcook.jpg",
        "title" : "Senior Account Executive",
        "content" : "Travis was one of the original employees from the beginning when FranchiseOpportunities.com launched in March 2000. Travis grew up in Flowery Branch, GA and graduated from Georgia State University with degree in Business Management. I'm very proud to be apart of a small company that has helped countless franchisors grow their businesses while at the same time having a part in helping entrepreneur's accomplish their dream of owning a business. Travis likes to spend his time away from the office with golf, archery, tinkering with vintage cars, and grilling dinner for his wife and teenage son."
    };

    var data = staffArray[name];
    // console.log(data);

    var aboutModal = '<div class="backdrop" onclick="closeModal(\'aboutModal\');"></div>';
    aboutModal += '<div class="guts">';
    aboutModal += '<div class="content">';
    aboutModal += '<div class="close_button" onclick="closeModal(\'aboutModal\');">&times;</div>';
    aboutModal += '<div class="about-modal-content">';
    aboutModal += '<div class="row about-modal-row">';
    aboutModal += '<div class="medium-3 mobile-12 pd-15">';
    aboutModal += '<img class="radius" src="'+data['img']+'" title="'+data['title']+'" alt="'+name+'"><hr>';
    aboutModal += '</div>';
    aboutModal += '<div class="medium-9 mobile-12 pd-15">';
    aboutModal += '<p>'+data['content']+'</p>';
    aboutModal += '</div></div></div></div></div>';

    document.getElementById('aboutModal').innerHTML = aboutModal;
    document.getElementById('aboutModal').style.display = 'block';
}