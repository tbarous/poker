import React, {FunctionComponent, useEffect, useState} from "react";
import {getAPI} from "../Api";
import styled from "styled-components";

const TR = styled.tr`
  border: 1px solid;
`

const TD = styled.td`
  border: 1px solid;
`

const TABLE = styled.table`
  width: 100%;
`;

const Statistics: FunctionComponent<{}> = () => {
    const [rounds, setRounds] = useState([])

    useEffect(() => {
        getAPI().statistics().then(h => {
            setRounds([...h.data])
        })
    })

    return (
        <TABLE>
            <thead>
            <tr>
                <th>ID</th>
                <th>Hands</th>
                <th>Winner</th>
            </tr>
            </thead>
            <tbody>
            {rounds.map(round => {
                return (
                    <TR key={round.id}>
                        <TD>
                            {round.id}
                        </TD>
                        <TD>
                            {round.hands.map(hand => {
                                return (
                                    <div key={hand.id}>
                                        <div>
                                            {hand.first_card.rank}{hand.first_card.suit}
                                        </div>

                                        <div>
                                            {hand.second_card.rank}{hand.second_card.suit}
                                        </div>

                                        <div>
                                            {hand.third_card.rank}{hand.third_card.suit}
                                        </div>

                                        <div>
                                            {hand.fourth_card.rank}{hand.fourth_card.suit}
                                        </div>

                                        <div>
                                            {hand.fifth_card.rank}{hand.fifth_card.suit}
                                        </div>

                                        <div>
                                            {hand.strength.name}
                                        </div>

                                        <hr/>
                                    </div>
                                )
                            })}
                        </TD>

                        <TD>
                            {round.winner.name}
                        </TD>
                    </TR>
                )
            })}
            </tbody>
        </TABLE>
    )
}

export default Statistics;