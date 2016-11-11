  <title>HomePik - Search J</title>
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
</head>
<body>
  <div id="header"></div>
  <div id="searchresultsheader"></div>
  <div id="wrapper"></div>
  <div id="criteriaFooter"></div>  
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
      <Content role={data.role} name={data.name} email={data.email} useragent={data.useragent} guestID={data.guestID} agentID={data.agentID} numSearches={data.numSearches} justRegSaveForm = {data.justRegSaveForm} />,
      document.getElementById("wrapper")
    );
    ReactDOM.render(
      <Footer mainPage={"criteria"} />,
      document.getElementById("criteriaFooter")
    );    
    ReactDOM.render(
      <Footer mainPage={"results"} />,
      document.getElementById("searchResultsFooter")
    );
  {/literal}
</script>
