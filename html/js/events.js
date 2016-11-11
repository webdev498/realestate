 <script type="text/jsx">
  /** @jsx React.DOM */
  
  var DialogContent = React.createClass({
    render: function(){
      return(
      <div>
        <form onSubmit={this.handleSubmit}>
          <input ref="inputText" />
          <input type="submit" />
          <button onClick = {this.props.closeDialog}>Cancel</button>
        </form>
      </div>
      )
    }
  });
  
  
  var DialogExample = React.createClass({
  
    openDialog: function(e){
      e.preventDefault();
      
      var $dialog = $('<div>').dialog({
        title: 'Example Dialog Title',
        width: 400,
        close: function(e){
          React.unmountAndReleaseReactRootNode(this);
          $( this ).remove();
        }
      });
      
      var closeDialog = function(e){
        e.preventDefault();
        $dialog.dialog('close');
      }
        
      React.renderComponent(<DialogContent closeDialog={closeDialog} />, $dialog[0])
    },
    render: function(){
      return(
          <button onClick= {this.openDialog}>Open Dialog</button>
      )
    }
  });
  
  React.renderComponent(<DialogExample />, document.getElementById('component'));
  
  </script>