<script type="text/template" id="item-table-row">
<tr>
	<td><input type="checkbox" name="list-single[]" data-id="<%=row.id%>"/></td>
	
<% _.each(header, function(i, v){ %>
	
	<td><%= row[i] %></td>
	
<% }); %>

	<td>
		<div class="btn-group">
		<a href="#" class="btn btn-mini btn-inverse" data-controller="common" data-action="edit">
			<i class="icon-white icon-pencil"></i>
		</a>
		<a href="#" class="btn btn-mini btn-inverse" data-controller="table" data-action="delete">
			<i class="icon-white icon-trash"></i>
		</a>
		<% _.each(moreActions, function(i, action){ %>
			<% if(i){%>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="table" data-action="<%=i %>">
				<i class="icon-white icon-picture"></i>
			</a>
			<%}%>
		<% }); %>		
		</div>
	</td>
</tr>
</script>