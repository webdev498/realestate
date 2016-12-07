	<title>HomePik - Address Search</title>
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <script> $(document).delegate('.ui-page', 'touchmove', false); </script>
</head>
<body>
  <div id="header"></div>
  <div id="searchresultsheader"></div>
  <div id="wrapper"></div>
  <div id="searchResultsFooter"></div>
  <div id="overlay"></div>  
  <div id="overlay2"></div>
<script type="text/babel">
  var data={$tplvar|@json_encode};
  {literal}
    ReactDOM.render(
      <SearchHeader role={data.role} name={data.name} email={data.email} guestID={data.guestID} />,
      document.getElementById("header")
    );
    ReactDOM.render(
      <SearchResultsHeader role={data.role} name={data.name} email={data.email} guestID={data.guestID} />,
      document.getElementById("searchresultsheader")
    );
    ReactDOM.render(
      <Content role={data.role} name={data.name} email={data.email} useragent={data.useragent} guestID={data.guestID} agentID={data.agentID} messages={data.messages} />,
      document.getElementById("wrapper")
    );    
    ReactDOM.render(
      <Footer mainPage={"address"} />,
      document.getElementById("searchResultsFooter")
    );
  {/literal}
</script>